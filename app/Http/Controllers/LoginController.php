<?php

namespace App\Http\Controllers;

use App\DataTransferObject\UserDTO;
use App\Services\Auth\Login\LoginService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    private $loginService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    function login(Request $request)
    {
        $user = new UserDTO;
        $user->setEmail($request->post('email'));
        $user->setPassword($request->post('password'));

        $result = $this->loginService->login($user);
        return $result;
    }

    function Logout(): JsonResponse
    {
        $result = $this->loginService->logout();
        return $result;
    }
}
