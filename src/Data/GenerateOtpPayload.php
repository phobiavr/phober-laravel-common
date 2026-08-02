<?php

namespace Phobiavr\PhoberLaravelCommon\Data;

class GenerateOtpPayload {
    public function __construct(
        public readonly int $digits,
        public readonly int $validity,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self {
        return new self((int) $data['digits'], (int) $data['validity']);
    }

    /** @return array<string, mixed> */
    public function toArray(): array {
        return [
            'digits'   => $this->digits,
            'validity' => $this->validity,
        ];
    }
}
