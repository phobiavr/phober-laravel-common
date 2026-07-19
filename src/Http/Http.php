<?php

namespace Phobiavr\PhoberLaravelCommon\Http;

use Illuminate\Support\Facades\Http as Facade;
use Phobiavr\PhoberLaravelCommon\Tracing\Tracer;

/**
 * Drop-in replacement for the `Http` facade: every call starts from a
 * request that already carries the tracing middleware, so callers don't
 * need to know or do anything about tracing themselves.
 *
 * @mixin Facade
 */
class Http {
    public static function __callStatic(string $method, array $args) {
        return Facade::withMiddleware(Tracer::httpMiddleware())
            ->acceptJson()
            ->withHeaders(['X-Service-Secret' => config('service.secret')])
            ->$method(...$args);
    }
}
