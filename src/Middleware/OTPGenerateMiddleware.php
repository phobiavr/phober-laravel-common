<?php

namespace Phobiavr\PhoberLaravelCommon\Middleware;

use Phobiavr\PhoberLaravelCommon\Clients\OtpClient;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OTPGenerateMiddleware {
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
        $otp = OtpClient::generateOtp();

        if (!$otp->success) {
            throw new AuthenticationException('Unauthorized');
        }

        $response = $next($request);
        $response->headers->set('X-OTP-Identifier', $otp->identifier);
        return $response;
    }
}
