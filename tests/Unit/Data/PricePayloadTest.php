<?php

namespace Tests\Unit\Data;

use InvalidArgumentException;
use Phobiavr\PhoberLaravelCommon\Data\PricePayload;
use Phobiavr\PhoberLaravelCommon\Enums\DeviceEnum;
use Phobiavr\PhoberLaravelCommon\Enums\SessionTariffEnum;
use Phobiavr\PhoberLaravelCommon\Enums\SessionTimeEnum;
use Tests\TestCase;

class PricePayloadTest extends TestCase
{
    public function test_for_instance_sets_instance_id_and_leaves_device_null(): void
    {
        $payload = PricePayload::forInstance(7, SessionTariffEnum::MORNING, SessionTimeEnum::MIN_30);

        $this->assertSame(7, $payload->instanceId);
        $this->assertNull($payload->device);
    }

    public function test_for_device_sets_device_and_leaves_instance_id_null(): void
    {
        $payload = PricePayload::forDevice(DeviceEnum::HTC, SessionTariffEnum::EVENING, SessionTimeEnum::MIN_60);

        $this->assertNull($payload->instanceId);
        $this->assertSame(DeviceEnum::HTC, $payload->device);
    }

    public function test_from_array_requires_instance_id_or_device(): void
    {
        $this->expectException(InvalidArgumentException::class);

        PricePayload::fromArray([
            'tariff' => SessionTariffEnum::MORNING->value,
            'time' => SessionTimeEnum::MIN_15->value,
        ]);
    }

    public function test_to_array_omits_null_fields(): void
    {
        $payload = PricePayload::forInstance(3, SessionTariffEnum::MORNING, SessionTimeEnum::MIN_15);

        $array = $payload->toArray();

        $this->assertSame(3, $array['instance_id']);
        $this->assertArrayNotHasKey('device', $array);
    }
}
