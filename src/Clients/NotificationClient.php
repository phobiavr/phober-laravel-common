<?php

namespace Phobiavr\PhoberLaravelCommon\Clients;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Phobiavr\PhoberLaravelCommon\Enums\NotificationChannel;
use Phobiavr\PhoberLaravelCommon\Enums\NotificationProvider;

class NotificationClient {
    protected static ?string $url = 'http://notification-server';

    public static function sendMessage(NotificationProvider $provider, NotificationChannel $channel, string $message): PromiseInterface|Response {
        return Http::accept('application/json')->post(self::$url . '/', [
            'provider' => $provider->value,
            'channel'  => $channel->value,
            'message'  => $message,
        ]);
    }
}
