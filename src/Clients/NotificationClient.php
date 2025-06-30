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
    private const TELEGRAM_BOT_NAME = 'phober_bot';

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
    public static function sendMessage(NotificationProvider $provider, string|NotificationChannel $channel, string $message): PromiseInterface|Response {
        return Http::accept('application/json')->post(self::getUrl() . '/', [
            'provider' => $provider->value,
            'channel'  => $channel instanceof NotificationChannel ? $channel->value : $channel,
            'message'  => $message,
        ]);
    }

    public static function generateShortLinkForTelegram(array $payload): string {
        $payload = http_build_query($payload);
        $encodedPayload = rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');
        
        return "https://t.me/" . self::TELEGRAM_BOT_NAME . "?start={$encodedPayload}";
    }
}
