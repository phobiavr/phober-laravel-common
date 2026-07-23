<?php

namespace Phobiavr\PhoberLaravelCommon\Middleware;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PrivateMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse|JsonResponse
     *
     * @throws AuthenticationException
     */
    public function handle(Request $request, \Closure $next) {
        if (hash_equals((string) config('service.secret'), (string) $request->header('X-Service-Secret'))) {
            return $next($request);
        }

        throw new AuthenticationException();
    }
}
