<?php

namespace Phobiavr\PhoberLaravelCommon\Clients;

use Illuminate\Http\Response;
use Phobiavr\PhoberLaravelCommon\Data\CheckSubmittedPayload;
use Phobiavr\PhoberLaravelCommon\Data\GenerateOtpPayload;
use Phobiavr\PhoberLaravelCommon\Data\ValidateOtpPayload;
use Phobiavr\PhoberLaravelCommon\Http\Http;

class OtpClient {
    protected static ?string $url = 'http://auth-server/otp';
    public int $digits = 4;
    public int $validity = 10;
    public string $identifier;
    public bool $success = false;

    public static function generateOtp(): self {
        $self = new self();

        $response = Http::post(self::$url . '/generate', (new GenerateOtpPayload($self->digits, $self->validity))->toArray());

        if ($response->status() === Response::HTTP_OK) {
            $self->identifier = $response['identifier'];
            $self->success = true;
        }

        return $self;
    }

    public static function validate(string $identifier, string $code = null): bool {
        if ($code) {
            $response = Http::post(self::$url . '/validate', (new ValidateOtpPayload($identifier, $code))->toArray());
        } else {
            $response = Http::post(self::$url . '/check-submitted', (new CheckSubmittedPayload($identifier))->toArray());
        }

        return $response->status() === Response::HTTP_OK;
    }
}
