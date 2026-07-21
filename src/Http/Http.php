<?php

namespace Phobiavr\PhoberLaravelCommon\Http;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http as Facade;
use Phobiavr\PhoberLaravelCommon\Exceptions\ServiceUnavailableException;
use Phobiavr\PhoberLaravelCommon\Tracing\Tracer;

/**
 * Drop-in replacement for the `Http` facade: every call starts from a
 * request that already carries the tracing middleware, so callers don't
 * need to know or do anything about tracing themselves.
 *
 * Connection-level failures (target service unreachable, DNS failure,
 * timeout) are normalized into ServiceUnavailableException, so every
 * client (DeviceClient, StaffClient, CrmClient, ...) surfaces the same
 * typed exception and callers only need to catch one thing.
 *
 * @mixin Facade
 */
class Http {
    public static function __callStatic(string $method, array $args) {
        try {
            return Facade::withMiddleware(Tracer::httpMiddleware())
                ->acceptJson()
                ->timeout(5)
                ->connectTimeout(3)
                ->withHeaders(['X-Service-Secret' => config('service.secret')])
                ->$method(...$args);
        } catch (ConnectionException $e) {
            throw new ServiceUnavailableException(self::serviceNameFromArgs($args), $e);
        }
    }

    private static function serviceNameFromArgs(array $args): string {
        $url = is_string($args[0] ?? null) ? $args[0] : '';

        return parse_url($url, PHP_URL_HOST) ?: 'unknown-service';
    }
}
