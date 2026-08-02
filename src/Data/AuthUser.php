<?php

namespace Phobiavr\PhoberLaravelCommon\Data;

use Illuminate\Contracts\Auth\Authenticatable;
use Phobiavr\PhoberLaravelCommon\Contracts\AuthUserInterface;

/**
 * Implements Authenticatable (in addition to AuthUserInterface) purely so it
 * can be handed to JsonGuard::setUser(), which is typed against Laravel's
 * Guard contract. There is no password/remember-me support here — this DTO
 * is never authenticated locally, only ever set externally after
 * AuthServerMiddleware has already validated the bearer token upstream.
 */
readonly class AuthUser implements AuthUserInterface, Authenticatable {
    /** @param array<int, string> $permissions */
    public function __construct(
        public int    $id,
        public string $username,
        public string $firstName,
        public string $lastName,
        public string $email,
        public array  $permissions = [],
    ) {
    }

    /** @param array<string, mixed> $data */
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

    /** @return array<int, string> */
    public function getPermissions(): array {
        return $this->permissions;
    }

    public function hasPermission(string $permission): bool {
        return in_array($permission, $this->permissions, true);
    }

    public function getAuthIdentifierName(): string {
        return 'id';
    }

    public function getAuthIdentifier(): int {
        return $this->id;
    }

    public function getAuthPasswordName(): string {
        return 'password';
    }

    public function getAuthPassword(): string {
        return '';
    }

    public function getRememberToken(): ?string {
        return null;
    }

    public function setRememberToken($value): void {
        // Not supported: this DTO is never authenticated locally.
    }

    public function getRememberTokenName(): string {
        return '';
    }
}
