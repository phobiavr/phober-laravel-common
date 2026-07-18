<?php

namespace Phobiavr\PhoberLaravelCommon\Data;

class AuthUser {
    public function __construct(
        public readonly int $id,
        public readonly string $username,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $email,
    ) {
    }

    public static function fromArray(array $data): self {
        return new self(
            (int) $data['id'],
            $data['username'],
            $data['first_name'],
            $data['last_name'],
            $data['email'],
        );
    }

    public function toArray(): array {
        return [
            'id'         => $this->id,
            'username'   => $this->username,
            'first_name' => $this->firstName,
            'last_name'  => $this->lastName,
            'email'      => $this->email,
        ];
    }
}
