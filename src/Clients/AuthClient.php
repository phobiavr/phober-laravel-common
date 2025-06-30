<?php

namespace Phobiavr\PhoberLaravelCommon\Clients;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

class AuthClient {
    protected static ?string $url = 'http://auth-server';

    public static function login() {
        $response = Http::accept('application/json')->withHeaders([
            'Authorization' => 'Bearer ' . request()->bearerToken()
        ])->get(self::$url . '/valid');

        return $response->status() === Response::HTTP_OK ? $response['user'] : null;
    }
    
    public static function linkTelegram(array $params): bool
    {
        $response = Http::accept('application/json')->withHeaders(['X-APP-KEY' => env('APP_KEY')])
            ->post(self::$url . '/link/telegram', $params);
        
        return $response->status() === Response::HTTP_OK;
    }
}
