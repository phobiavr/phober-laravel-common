<?php

namespace Phobiavr\PhoberLaravelCommon\Data;

class ValidateOtpPayload {
    public function __construct(
        public readonly string $identifier,
        public readonly string $code,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self {
        return new self($data['identifier'], $data['code']);
    }

    /** @return array<string, mixed> */
    public function toArray(): array {
        return [
            'identifier' => $this->identifier,
            'code'       => $this->code,
        ];
    }
}
