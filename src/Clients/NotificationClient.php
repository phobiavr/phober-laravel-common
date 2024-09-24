<?php

namespace Phobiavr\PhoberLaravelCommon\Clients;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Phobiavr\PhoberLaravelCommon\Enums\NotificationChannel;
use Phobiavr\PhoberLaravelCommon\Enums\NotificationProvider;

class NotificationClient {
    protected static ?string $url = null;

    public static function getUrl(): string {
        return self::$url ?? env('NOTIFICATION_SERVER_URL', 'http://notification-server');
    }

    /**
     * @param NotificationProvider $provider
     * @param NotificationChannel $channel
     * @param string $message
     * @return PromiseInterface|Response
     * @throws ConnectionException
     */
    public static function sendMessage(NotificationProvider $provider, NotificationChannel $channel, string $message): PromiseInterface|Response {
        return Http::accept('application/json')->post(self::getUrl() . '/', [
            'provider' => $provider->value,
            'channel'  => $channel->value,
            'message'  => $message,
        ]);
    }
}
