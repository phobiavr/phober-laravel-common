<?php

namespace Phobiavr\PhoberLaravelCommon\Middleware;

use Phobiavr\PhoberLaravelCommon\Clients\AuthClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthServerMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse|JsonResponse
     */
    public function handle(Request $request, \Closure $next) {
        if ($user = AuthClient::login()) {
            Auth::guard('server')->setUser($user);

            return $next($request);
        }

        return response()->json(['message' => 'Credentials error'], 401);
    }
}
