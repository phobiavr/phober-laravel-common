<?php

namespace Phobiavr\PhoberLaravelCommon\Logging;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;
use Phobiavr\PhoberLaravelCommon\Tracing\Tracer;

/**
 * Stamps every log record with the current OpenTelemetry trace/span id, so
 * log lines from different services can be correlated back to one request
 * via `trace_id`. No-op (record passes through unchanged) when there is no
 * active span.
 */
class TraceIdProcessor implements ProcessorInterface {
    public function __invoke(LogRecord $record): LogRecord {
        $traceId = Tracer::currentTraceId();

        if (!$traceId) {
            return $record;
        }

        return $record->with(extra: [
            ...$record->extra,
            'trace_id' => $traceId,
            'span_id'  => Tracer::currentSpanId(),
        ]);
    }
}
