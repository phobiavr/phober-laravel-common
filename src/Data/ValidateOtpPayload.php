<?php

namespace Phobiavr\PhoberLaravelCommon\Data;

class ValidateOtpPayload {
    public function __construct(
        public readonly string $identifier,
        public readonly string $code,
    ) {
    }

    public static function fromArray(array $data): self {
        return new self($data['identifier'], $data['code']);
    }

    public function toArray(): array {
        return [
            'identifier' => $this->identifier,
            'code'       => $this->code,
        ];
    }
}
