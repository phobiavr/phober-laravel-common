<?php

namespace Phobiavr\PhoberLaravelCommon\Data;

use Phobiavr\PhoberLaravelCommon\Contracts\AuthUserInterface;

readonly class AuthUser implements AuthUserInterface {
    public function __construct(
        public int    $id,
        public string $username,
        public string $firstName,
        public string $lastName,
        public string $email,
        public array  $permissions = [],
    ) {
    }

    public static function fromArray(array $data): self {
        return new self(
            (int) $data[self::FIELD_ID],
            $data[self::FIELD_USERNAME],
            $data[self::FIELD_FIRST_NAME],
            $data[self::FIELD_LAST_NAME],
            $data[self::FIELD_EMAIL],
            $data[self::FIELD_PERMISSIONS] ?? [],
        );
    }

    public function getId(): int {
        return $this->id;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getFirstName(): string {
        return $this->firstName;
    }

    public function getLastName(): string {
        return $this->lastName;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPermissions(): array {
        return $this->permissions;
    }

    public function hasPermission(string $permission): bool {
        return in_array($permission, $this->permissions, true);
    }
}
