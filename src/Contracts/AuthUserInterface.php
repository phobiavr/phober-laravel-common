<?php

namespace Phobiavr\PhoberLaravelCommon\Contracts;

interface AuthUserInterface {
    public const FIELD_ID = 'id';
    public const FIELD_USERNAME = 'username';
    public const FIELD_FIRST_NAME = 'first_name';
    public const FIELD_LAST_NAME = 'last_name';
    public const FIELD_EMAIL = 'email';
    public const FIELD_PERMISSIONS = 'permissions';

    public function getId(): int;

    public function getUsername(): string;

    public function getFirstName(): string;

    public function getLastName(): string;

    public function getEmail(): string;

    public function getPermissions(): array;

    public function hasPermission(string $permission): bool;
}
