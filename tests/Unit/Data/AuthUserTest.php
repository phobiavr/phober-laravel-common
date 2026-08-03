<?php

namespace Tests\Unit\Data;

use Phobiavr\PhoberLaravelCommon\Data\AuthUser;
use Tests\TestCase;

class AuthUserTest extends TestCase
{
    public function test_from_array_and_to_array_round_trip(): void
    {
        $data = [
            'id' => '5',
            'username' => 'staff.member',
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.test',
            'permissions' => ['manage_discount'],
        ];

        $user = AuthUser::fromArray($data);

        $this->assertSame(5, $user->id);
        $this->assertSame('staff.member', $user->username);
        $this->assertTrue($user->hasPermission('manage_discount'));
        $this->assertFalse($user->hasPermission('something_else'));
    }

    public function test_from_array_defaults_permissions_to_empty_array(): void
    {
        $user = AuthUser::fromArray([
            'id' => 1,
            'username' => 'x',
            'first_name' => 'X',
            'last_name' => 'Y',
            'email' => 'x@example.test',
        ]);

        $this->assertSame([], $user->getPermissions());
        $this->assertFalse($user->hasPermission('anything'));
    }

    public function test_auth_identifier_matches_id(): void
    {
        $user = new AuthUser(42, 'u', 'F', 'L', 'e@example.test');

        $this->assertSame(42, $user->getAuthIdentifier());
        $this->assertSame('id', $user->getAuthIdentifierName());
        $this->assertNull($user->getRememberToken());
    }
}
