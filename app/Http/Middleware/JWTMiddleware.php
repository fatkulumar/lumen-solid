<?php

namespace App\Http\Middleware;

use App\Models\Burn_token;
use App\Models\BurnToken;
use Closure;
use App\Traits\ResultService;
use Illuminate\Http\JsonResponse;

class JWTMiddleware
{
    use ResultService;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $token = $request->bearerToken();
        $burn_token = BurnToken::where('token', $token)->first();
        $path = $request->getPathInfo();

        $allowedPaths = [
            '/login',
            '/'
        ];

        if(in_array($path, $allowedPaths)) {
            return $next($request);
        }

        if (!$token || is_null($token) || $burn_token) {
            $this->setResult(null)
            ->setStatus(true)
            ->setMessage('Silakan melakukan proses authentifikasi terlebih dahulu')
            ->setCode(JsonResponse::HTTP_OK);

            return $this->toJson();
        }

        return $next($request);
    }
}
