<?php

namespace Phobiavr\PhoberLaravelCommon\Clients;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Phobiavr\PhoberLaravelCommon\Data\SendMessagePayload;
use Phobiavr\PhoberLaravelCommon\Http\Http;
use Phobiavr\PhoberLaravelCommon\Enums\NotificationChannel;
use Phobiavr\PhoberLaravelCommon\Enums\NotificationProvider;

class NotificationClient {
    protected static ?string $url = null;
    private const TELEGRAM_BOT_NAME = 'phober_bot';

    public static function getUrl(): string {
        return self::$url ?? (string) config('service.notification_url', 'http://notification-server');
    }

    /**
     * @throws ConnectionException
     */
    public static function sendMessage(NotificationProvider $provider, NotificationChannel $channel, string $message): Response {
        return Http::post(self::getUrl() . '/', (new SendMessagePayload($provider, $channel, $message))->toArray());
    }

    /** @param array<string, mixed> $payload */
    public static function generateShortLinkForTelegram(array $payload): string {
        $payload = http_build_query($payload);
        $encodedPayload = rtrim(strtr(base64_encode($payload), '+/', '-_'), '=');

        return "https://t.me/" . self::TELEGRAM_BOT_NAME . "?start={$encodedPayload}";
    }
}
