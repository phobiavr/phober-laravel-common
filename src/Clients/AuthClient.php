<?php

namespace Phobiavr\PhoberLaravelCommon\Clients;

use Illuminate\Http\Response;
use Phobiavr\PhoberLaravelCommon\Data\AuthUser;
use Phobiavr\PhoberLaravelCommon\Http\Http;

class AuthClient {
    protected static ?string $url = 'http://auth-server';

    public static function login(): ?AuthUser {
        $response = Http::withToken(request()->bearerToken())->get(self::$url . '/valid');

        return $response->status() === Response::HTTP_OK ? AuthUser::fromArray($response['user']) : null;
    }

    public static function user(?int $id): ?AuthUser {
        if (is_null($id)) {
            return null;
        }

        static $cache = [];

        if (array_key_exists($id, $cache)) {
            return $cache[$id];
        }

        $response = Http::get(self::$url . '/users/' . $id);

        return $cache[$id] = $response->status() === Response::HTTP_OK ? AuthUser::fromArray($response['user']) : null;
    }

    public static function linkTelegram(array $params): bool
    {
        $response = Http::post(self::$url . '/link/telegram', $params);

        return $response->status() === Response::HTTP_OK;
    }
}
