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

    public static function fromArray(array $data): self {
        return new self(
            NotificationProvider::from($data['provider']),
            NotificationChannel::from($data['channel']),
            $data['message'],
        );
    }

    public function toArray(): array {
        return [
            'provider' => $this->provider->value,
            'channel'  => $this->channel->value,
            'message'  => $this->message,
        ];
    }
}
