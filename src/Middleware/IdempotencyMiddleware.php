<?php

namespace Phobiavr\PhoberLaravelCommon\Middleware;

use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Phobiavr\PhoberLaravelCommon\IdempotencyKey;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Throwable;

/**
 * Deduplicates mutating requests carrying an `Idempotency-Key` header.
 * Opt-in: requests without the header are unaffected.
 */
class IdempotencyMiddleware {
    public function handle(Request $request, \Closure $next) {
        $key = $request->header('Idempotency-Key');

        if (!$key) {
            return $next($request);
        }

        $scope = $request->method() . ' ' . $request->path();
        $requestHash = hash('sha256', (string) $request->getContent());

        try {
            $record = IdempotencyKey::query()->create([
                'scope'        => $scope,
                'key'          => $key,
                'request_hash' => $requestHash,
            ]);
        } catch (QueryException $e) {
            if (!str_starts_with((string) $e->getCode(), '23')) {
                throw $e;
            }

            return $this->replay($scope, $key, $requestHash);
        }

        try {
            $response = $next($request);
        } catch (Throwable $e) {
            $record->delete();

            throw $e;
        }

        $record->update([
            'response_status'       => $response->getStatusCode(),
            'response_content_type' => $response->headers->get('Content-Type'),
            'response_body'         => $response->getContent(),
        ]);

        return $response;
    }

    /**
     * @return Response|RedirectResponse|JsonResponse
     */
    private function replay(string $scope, string $key, string $requestHash) {
        $existing = IdempotencyKey::query()->where('scope', $scope)->where('key', $key)->first();

        if (!$existing || $existing->isPending()) {
            throw new ConflictHttpException('A request with this Idempotency-Key is already being processed.');
        }

        if ($existing->request_hash !== $requestHash) {
            throw new ConflictHttpException('This Idempotency-Key was already used with a different request payload.');
        }

        return response($existing->response_body, $existing->response_status)
            ->header('Content-Type', $existing->response_content_type ?? 'application/json')
            ->header('Idempotency-Replayed', 'true');
    }
}
