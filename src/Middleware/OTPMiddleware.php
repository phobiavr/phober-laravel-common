<?php

namespace Phobiavr\PhoberLaravelCommon\Middleware;

use Phobiavr\PhoberLaravelCommon\Clients\OtpClient;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OTPMiddleware {
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
        $identifier = $request->header('X-OTP-Identifier') ?? null;
        $code = $request->header('X-OTP-Code') ?? null;

        if (!$identifier || !OtpClient::validate($identifier, $code)) {
            throw new AuthenticationException('Unauthorized');
        }

        return $next($request);
    }
}
