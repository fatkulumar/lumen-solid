<?php

namespace App\Traits;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Traits\ResultService;
use Firebase\JWT\ExpiredException;
use Illuminate\Http\JsonResponse;

trait MatchedJWT
{
    use ResultService;

    public function matchedJwt($token, $level)
    {
        try {
					
            $credentials = JWT::decode($token, new Key(env("APP_KEY"), env('HASH_JWT')));
            // Consume both level JWT (admin & member)
            if ($level == 'both') {
                $level = $credentials->sub->level;
            }

            // Matched JWT level
            if ($credentials->sub->level !== $level) {

                $this->setResult(null)
                ->setStatus(true)
                ->setMessage('Tidak Memiliki Akses')
                ->setCode(JsonResponse::HTTP_UNAUTHORIZED);
                return $this->toJson();
            }

            $id = $credentials->sub->id;
            switch ($level) {
                case 'member':
                    $data = User::where('id_user', $id)->first();
                    $this->setResult($data)
                        ->setStatus(true)
                        ->setMessage('Profil Berhasil Ditemukan')
                        ->setCode(JsonResponse::HTTP_OK);
                    break;
                case 'admin':
                    $data = User::where('id_admin', $id)->first();
                    $this->setResult($data)
                        ->setStatus(true)
                        ->setMessage('Profil Berhasil Ditemukan')
                        ->setCode(JsonResponse::HTTP_OK);
                    break;
                default:
                    $this->setResult(null)
                        ->setStatus(true)
                        ->setMessage('Tidak Memiliki Akses')
                        ->setCode(JsonResponse::HTTP_UNAUTHORIZED);
                    break;
            }
        } catch (ExpiredException $exception) {
            $this->setResult($data)
                ->setStatus(true)
                ->setMessage('Token Kadaluarsa')
                ->setCode(JsonResponse::HTTP_UNAUTHORIZED);
        } catch (\Exception $exception) {
            $this->exceptionResponse($exception);
        }
        return $this->toJson();
    }
}