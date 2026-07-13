<?php

namespace Phobiavr\PhoberLaravelCommon\Data;

class CheckSubmittedPayload {
    public function __construct(
        public readonly string $identifier,
    ) {
    }

    public static function fromArray(array $data): self {
        return new self($data['identifier']);
    }

    public function toArray(): array {
        return [
            'identifier' => $this->identifier,
        ];
    }
}
