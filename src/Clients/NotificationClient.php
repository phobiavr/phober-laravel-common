<?php

namespace Phobiavr\PhoberLaravelCommon\Clients;

use Phobiavr\PhoberLaravelCommon\Enums\NotificationChannel;
use Phobiavr\PhoberLaravelCommon\Enums\NotificationProvider;
use Illuminate\Support\Facades\Http;

class NotificationClient {
    protected static ?string $url = 'http://notification-server';

    public static function sendMessage(NotificationProvider $provider, NotificationChannel $channel, string $message): void {
        Http::accept('application/json')->post(self::$url . '/', [
            'provider' => $provider->value,
            'channel'  => $channel->value,
            'message'  => $message,
        ]);
    }
}
