<?php

namespace Phobiavr\PhoberLaravelCommon\Clients;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Phobiavr\PhoberLaravelCommon\Http\Http;

class StaffClient {
    protected static ?string $url = 'http://staff-service';

    public static function sessionById(int $sessionId): PromiseInterface|Response {
        return Http::get(self::$url . '/sessions/' . $sessionId);
    }
}
