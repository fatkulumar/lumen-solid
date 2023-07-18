<?php

namespace App\Traits;

use Carbon\Carbon;
use Firebase\JWT\JWT;

trait GenerateJWT
{
    public function generateJWT($data)
    {
        $key = env('APP_KEY');
        $payload = [
            'iss' => 'SOLID Lumen',
            'sub' => $data,
            'iat' => time(),
            // 'exp' => time() + (60 * 60) * 24
            'exp' => Carbon::now()->addDay(env('JWT_EXPIRED_TIME') ?? 1)->timestamp
        ];

        return JWT::encode($payload, $key, env('HASH_JWT'));
    }
}