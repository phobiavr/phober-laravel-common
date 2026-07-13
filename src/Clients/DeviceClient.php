<?php

namespace Phobiavr\PhoberLaravelCommon\Clients;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Phobiavr\PhoberLaravelCommon\Data\PricePayload;
use Phobiavr\PhoberLaravelCommon\Data\SchedulePayload;
use Phobiavr\PhoberLaravelCommon\Http\Http;

class DeviceClient {
    protected static ?string $url = 'http://device-service';

    public static function schedule(SchedulePayload $payload): PromiseInterface|Response {
        return Http::post(self::$url . '/schedule', $payload->toArray());
    }

    public static function deleteSchedule(int $scheduleId): PromiseInterface|Response {
        return Http::delete(self::$url . '/schedule/' . $scheduleId);
    }

    public static function price(PricePayload $payload): PromiseInterface|Response {
        return Http::post(self::$url . '/price', $payload->toArray());
    }
}
