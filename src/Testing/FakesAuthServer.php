<?php

namespace Phobiavr\PhoberLaravelCommon\Testing;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

/**
 * Fakes the `auth.server` middleware's upstream call to auth-server's
 * GET /valid, so `Route::middleware('auth.server')` routes can be hit in
 * feature tests without a real auth-server. Registered once in setUp() and
 * unauthorized by default — call authorizeAuthServer() to opt a test into a
 * 200 with a fake user. Other Http::fake() calls in the test (e.g. for
 * device-service/crm-service) compose fine on top of this, since the
 * closure returns null (falls through) for any other URL.
 */
trait FakesAuthServer
{
    protected bool $authServerAuthorized = false;
    protected array $authServerPermissions = [];
    protected array $authServerOverrides = [];

    protected function setUpFakesAuthServer(): void
    {
        Http::fake(fn (Request $request) => $request->url() !== 'http://auth-server/valid' ? null : (
            $this->authServerAuthorized
                ? Http::response(['user' => array_merge([
                    'id' => 1,
                    'username' => 'staff.member',
                    'first_name' => 'Test',
                    'last_name' => 'User',
                    'email' => 'test@example.test',
                    'permissions' => $this->authServerPermissions,
                ], $this->authServerOverrides)], 200)
                : Http::response(null, 401)
        ));
    }

    protected function authorizeAuthServer(array $permissions = [], array $overrides = []): void
    {
        $this->authServerAuthorized = true;
        $this->authServerPermissions = $permissions;
        $this->authServerOverrides = $overrides;
    }
}
