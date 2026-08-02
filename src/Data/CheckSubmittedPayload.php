<?php

namespace Phobiavr\PhoberLaravelCommon\Data;

class CheckSubmittedPayload {
    public function __construct(
        public readonly string $identifier,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self {
        return new self($data['identifier']);
    }

    /** @return array<string, mixed> */
    public function toArray(): array {
        return [
            'identifier' => $this->identifier,
        ];
    }
}
