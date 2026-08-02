<?php

namespace Phobiavr\PhoberLaravelCommon\Data;

use Phobiavr\PhoberLaravelCommon\Enums\NotificationChannel;
use Phobiavr\PhoberLaravelCommon\Enums\NotificationProvider;

class SendMessagePayload {
    public function __construct(
        public readonly NotificationProvider $provider,
        public readonly NotificationChannel $channel,
        public readonly string $message,
    ) {
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self {
        return new self(
            NotificationProvider::from($data['provider']),
            NotificationChannel::from($data['channel']),
            $data['message'],
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array {
        return [
            'provider' => $this->provider->value,
            'channel'  => $this->channel->value,
            'message'  => $this->message,
        ];
    }
}
