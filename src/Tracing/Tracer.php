<?php

namespace Phobiavr\PhoberLaravelCommon\Tracing;

use OpenTelemetry\API\Globals;
use OpenTelemetry\API\Trace\Propagation\TraceContextPropagator;
use OpenTelemetry\API\Trace\Span;
use OpenTelemetry\API\Trace\SpanKind;
use OpenTelemetry\API\Trace\StatusCode;
use OpenTelemetry\Context\Context;
use Throwable;

/**
 * Thin wrapper around the OpenTelemetry SDK. The `open-telemetry/*` packages
 * are only required by services that opted into tracing (staff-service,
 * device-service) — SharedServiceProvider wires this into every service, so
 * every method here must degrade to a no-op when the SDK isn't installed.
 */
class Tracer {
    protected static function enabled(): bool {
        return class_exists(Globals::class);
    }

    protected static function tracer() {
        return Globals::tracerProvider()->getTracer('phober');
    }

    protected static function propagator(): TraceContextPropagator {
        return TraceContextPropagator::getInstance();
    }

    /**
     * W3C traceparent/tracestate headers for the currently active span,
     * to be merged into an outgoing request or a job payload.
     */
    public static function currentTraceHeaders(): array {
        if (!static::enabled()) {
            return [];
        }

        $carrier = [];
        static::propagator()->inject($carrier, null, Context::getCurrent());

        return $carrier;
    }

    /**
     * Trace id of the currently active span, for correlating log lines
     * across services. Null when there is no active span (SDK not loaded,
     * or running outside any traced request/job/command context).
     */
    public static function currentTraceId(): ?string {
        if (!static::enabled()) {
            return null;
        }

        $context = Span::getCurrent()->getContext();

        return $context->isValid() ? $context->getTraceId() : null;
    }

    /**
     * Span id of the currently active span. Same null-safety as
     * currentTraceId().
     */
    public static function currentSpanId(): ?string {
        if (!static::enabled()) {
            return null;
        }

        $context = Span::getCurrent()->getContext();

        return $context->isValid() ? $context->getSpanId() : null;
    }

    protected static function extractContext(?array $carrier): Context {
        if (!$carrier) {
            return Context::getCurrent();
        }

        $flat = array_map(static fn($value) => is_array($value) ? ($value[0] ?? '') : $value, $carrier);

        return static::propagator()->extract($flat, null, Context::getCurrent());
    }

    protected static function run(callable $spanBuilder, callable $callback) {
        if (!static::enabled()) {
            return $callback();
        }

        $span = $spanBuilder();
        $scope = $span->activate();

        try {
            $result = $callback();

            if (is_object($result) && method_exists($result, 'status')) {
                $span->setAttribute('http.status_code', $result->status());
            } elseif (is_object($result) && method_exists($result, 'getStatusCode')) {
                $span->setAttribute('http.status_code', $result->getStatusCode());
                if ($result->getStatusCode() >= 500) {
                    $span->setStatus(StatusCode::STATUS_ERROR);
                }
            }

            return $result;
        } catch (Throwable $e) {
            $span->recordException($e);
            $span->setStatus(StatusCode::STATUS_ERROR, $e->getMessage());
            throw $e;
        } finally {
            $span->end();
            $scope->detach();
        }
    }

    /**
     * Wrap an inbound HTTP request in a SERVER span, using trace headers
     * extracted from the caller (if any) as the parent context.
     */
    public static function withServerSpan(string $name, array $headers, callable $callback, array $attributes = []) {
        return static::run(
            fn() => static::tracer()->spanBuilder($name)
                ->setParent(static::extractContext($headers))
                ->setSpanKind(SpanKind::KIND_SERVER)
                ->setAttributes($attributes)
                ->startSpan(),
            $callback
        );
    }

    /**
     * Guzzle-style middleware that auto-instruments every outgoing HTTP call
     * made through it: reads the method/URI off the actual request to name
     * and tag the span, and injects W3C trace headers — callers don't need
     * to know or do anything about tracing themselves.
     */
    public static function httpMiddleware(): callable {
        return function (callable $handler) {
            return function ($request, array $options) use ($handler) {
                if (!static::enabled()) {
                    return $handler($request, $options);
                }

                $span = static::tracer()->spanBuilder($request->getMethod() . ' ' . $request->getUri()->getHost() . $request->getUri()->getPath())
                    ->setSpanKind(SpanKind::KIND_CLIENT)
                    ->setAttributes([
                        'http.method' => $request->getMethod(),
                        'http.url'    => (string) $request->getUri(),
                    ])
                    ->startSpan();
                $scope = $span->activate();

                foreach (static::currentTraceHeaders() as $key => $value) {
                    $request = $request->withHeader($key, $value);
                }

                $end = static function ($span, $scope) {
                    $span->end();
                    $scope->detach();
                };

                return $handler($request, $options)->then(
                    static function ($response) use ($span, $end, $scope) {
                        $span->setAttribute('http.status_code', $response->getStatusCode());
                        if ($response->getStatusCode() >= 500) {
                            $span->setStatus(StatusCode::STATUS_ERROR);
                        }
                        $end($span, $scope);
                        return $response;
                    },
                    static function ($reason) use ($span, $end, $scope) {
                        if ($reason instanceof Throwable) {
                            $span->recordException($reason);
                            $span->setStatus(StatusCode::STATUS_ERROR, $reason->getMessage());
                        }
                        $end($span, $scope);
                        throw $reason;
                    }
                );
            };
        };
    }

    /**
     * Wrap a queue job handler in a CONSUMER span, continuing the trace
     * that was active when the job was dispatched (via $traceHeaders).
     *
     * `php artisan queue:work` is a long-running process that never hits
     * PHP's normal request-shutdown flush between jobs, so the batch span
     * processor would otherwise sit on this span indefinitely — force an
     * export right after each job instead of waiting for one.
     */
    public static function withConsumerSpan(string $name, ?array $traceHeaders, callable $callback, array $attributes = []) {
        $result = static::run(
            fn() => static::tracer()->spanBuilder($name)
                ->setParent(static::extractContext($traceHeaders))
                ->setSpanKind(SpanKind::KIND_CONSUMER)
                ->setAttributes($attributes)
                ->startSpan(),
            $callback
        );

        if (static::enabled()) {
            Globals::tracerProvider()->forceFlush();
        }

        return $result;
    }
}
