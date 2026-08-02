<?php

namespace Phobiavr\PhoberLaravelCommon\Data;

use DateTime;
use Phobiavr\PhoberLaravelCommon\Enums\ScheduleEnum;

class SchedulePayload {
    public function __construct(
        public readonly ScheduleEnum $type,
        public readonly int $instanceId,
        public readonly DateTime $start,
        public readonly ?DateTime $end = null,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self {
        return new self(
            ScheduleEnum::from($data['type']),
            (int) $data['instance_id'],
            new DateTime($data['start']),
            isset($data['end']) ? new DateTime($data['end']) : null,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array {
        return [
            'type'        => $this->type->value,
            'instance_id' => $this->instanceId,
            'start'       => $this->start->format('Y-m-d H:i:s'),
            'end'         => $this->end?->format('Y-m-d H:i:s'),
        ];
    }
}
