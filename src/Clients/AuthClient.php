<?php

namespace Abdukhaligov\PhoberLaravelCommon\Clients;

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
}
