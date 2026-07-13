<?php

namespace Phobiavr\PhoberLaravelCommon\Clients;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Phobiavr\PhoberLaravelCommon\Http\Http;

class CrmClient {
    protected static ?string $url = 'http://crm-service';

    public static function customer(int $customerId): PromiseInterface|Response {
        return Http::get(self::$url . '/customers/' . $customerId);
    }
}
