<?php

namespace Tests\Unit;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Phobiavr\PhoberLaravelCommon\Data\AuthUser;
use Phobiavr\PhoberLaravelCommon\JsonGuard;
use Tests\TestCase;

class JsonGuardTest extends TestCase
{
    public function test_guest_by_default(): void
    {
        $guard = new JsonGuard($this->createMock(UserProvider::class), new Request());

        $this->assertTrue($guard->guest());
        $this->assertFalse($guard->check());
        $this->assertFalse($guard->hasUser());
        $this->assertNull($guard->user());
        $this->assertNull($guard->id());
    }

    public function test_set_user_makes_it_authenticated(): void
    {
        $guard = new JsonGuard($this->createMock(UserProvider::class), new Request());
        $user = new AuthUser(9, 'u', 'F', 'L', 'e@example.test');

        $guard->setUser($user);

        $this->assertTrue($guard->check());
        $this->assertFalse($guard->guest());
        $this->assertTrue($guard->hasUser());
        $this->assertSame($user, $guard->user());
        $this->assertSame(9, $guard->id());
    }

    public function test_validate_never_authenticates_locally(): void
    {
        $guard = new JsonGuard($this->createMock(UserProvider::class), new Request());

        $this->assertFalse($guard->validate(['anything' => 'goes']));
    }
}
