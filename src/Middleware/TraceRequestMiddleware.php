<?php

namespace Phobiavr\PhoberLaravelCommon\Middleware;

use Closure;
use Illuminate\Http\Request;
use Phobiavr\PhoberLaravelCommon\Tracing\Tracer;

class TraceRequestMiddleware {
    /** @return \Symfony\Component\HttpFoundation\Response */
    public function handle(Request $request, Closure $next) {
        return Tracer::withServerSpan($request->method() . ' ' . $request->path(), $request->headers->all(), function () use ($request, $next) {
            return $next($request);
        }, [
            'http.method' => $request->method(),
            'http.target' => $request->path(),
            'http.host'   => $request->getHost(),
        ]);
    }
}
