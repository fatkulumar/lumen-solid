<?php

namespace App\Http\Controllers;

use App\DataTransferObject\UserDTO;
use App\Services\Auth\Logout\LogoutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    private $logoutService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    function __construct(LogoutService $logoutService)
    {
        $this->logoutService = $logoutService;
    }

    function logout(Request $request): JsonResponse
    {
        $user = new UserDTO;
        $user->setToken($request->bearerToken());
        $result = $this->logoutService->logout($user);
        return $result;
    }
}
