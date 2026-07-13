<?php

namespace Phobiavr\PhoberLaravelCommon\Data;

use InvalidArgumentException;
use Phobiavr\PhoberLaravelCommon\Enums\DeviceEnum;
use Phobiavr\PhoberLaravelCommon\Enums\SessionTariffEnum;
use Phobiavr\PhoberLaravelCommon\Enums\SessionTimeEnum;

class PricePayload {
    private function __construct(
        public readonly ?int $instanceId,
        public readonly ?DeviceEnum $device,
        public readonly SessionTariffEnum $tariff,
        public readonly SessionTimeEnum $time,
    ) {
    }

    public static function forInstance(int $instanceId, SessionTariffEnum $tariff, SessionTimeEnum $time): self {
        return new self($instanceId, null, $tariff, $time);
    }

    public static function forDevice(DeviceEnum $device, SessionTariffEnum $tariff, SessionTimeEnum $time): self {
        return new self(null, $device, $tariff, $time);
    }

    public static function fromArray(array $data): self {
        $instanceId = isset($data['instance_id']) ? (int) $data['instance_id'] : null;
        $device     = isset($data['device']) ? DeviceEnum::from($data['device']) : null;

        if ($instanceId === null && $device === null) {
            throw new InvalidArgumentException('PricePayload requires either instance_id or device.');
        }

        return new self(
            $instanceId,
            $device,
            SessionTariffEnum::from($data['tariff']),
            SessionTimeEnum::from($data['time']),
        );
    }

    public function toArray(): array {
        return array_filter([
            'instance_id' => $this->instanceId,
            'device'      => $this->device?->value,
            'tariff'      => $this->tariff->value,
            'time'        => $this->time->value,
        ], static fn($value) => $value !== null);
    }
}
