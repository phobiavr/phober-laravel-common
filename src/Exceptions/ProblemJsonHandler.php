<?php

namespace Phobiavr\PhoberLaravelCommon\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

/**
 * Renders every exception as an RFC 7807 (application/problem+json) body, so
 * all services return the same error shape regardless of which middleware or
 * layer raised the failure.
 */
class ProblemJsonHandler {
    public static function register(Exceptions $exceptions): void {
        $exceptions->render(function (ValidationException $e, Request $request) {
            return self::problem($request, 422, 'The given data was invalid.', [
                'errors' => $e->errors(),
            ]);
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return self::problem($request, 401, $e->getMessage() ?: 'Unauthenticated.');
        });

        $exceptions->render(function (ServiceUnavailableException $e, Request $request) {
            return self::problem($request, 503, $e->getMessage());
        });

        $exceptions->render(function (HttpExceptionInterface $e, Request $request) {
            $status = $e->getStatusCode();

            return self::problem($request, $status, $e->getMessage() ?: (Response::$statusTexts[$status] ?? 'Error'));
        });

        $exceptions->render(function (Throwable $e, Request $request) {
            $detail = config('app.debug') ? $e->getMessage() : 'Server Error.';

            return self::problem($request, 500, $detail ?: 'Server Error.');
        });
    }

    /** @param array<string, mixed> $extra */
    private static function problem(Request $request, int $status, string $detail, array $extra = []): JsonResponse {
        $title = Response::$statusTexts[$status] ?? 'Error';

        $body = array_merge([
            'type'     => 'urn:phober:problem:' . Str::slug($title),
            'title'    => $title,
            'status'   => $status,
            'detail'   => $detail,
            'message'  => $detail,
            'instance' => '/' . ltrim($request->path(), '/'),
        ], $extra);

        return response()->json($body, $status, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            ->header('Content-Type', 'application/problem+json');
    }
}
