<?php

namespace App\Http\Controllers;

use App\DataTransferObject\UserDTO;
use App\Services\User\UserService;
use App\Http\Controllers\UserInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userService;
    /**
     * Create a new controller instance.
     *
     * @return void
    */
    function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * Get all data.
     *
     * @return JsonResponse
    */
    function all(): JsonResponse
    {
        $result = $this->userService->all();
        return $result;
    }
    /**
     * Get By Id data.
     *
     * @return JsonResponse
    */
    function getById(Request $request): JsonResponse
    {
        $user = new UserDTO;
        $user->setId($request->get('id'));
        $result = $this->userService->getById($user);
        return $result;
    }
    /**
     * Create.
     *
     * @return JsonResponse
    */
    function create(Request $request): JsonResponse
    {
        $user = new UserDTO;
        $user->setName($request->post('name'));
        $user->setEmail($request->post('email'));
        $user->setPassword($request->post('password'));
        $user->setFile($request->file('foto'));
        $result = $this->userService->create($user);
        return $result;
    }
    /**
     * Update.
     *
     * @return JsonResponse
    */
    function Update(Request $request): JsonResponse
    {
        $user = new UserDTO;
        $user->setId($request->post('id'));
        $user->setName($request->post('name'));
        $user->setEmail($request->post('email'));
        $user->setPassword($request->post('password'));
        $user->setFile($request->file('foto'));
        $result = $this->userService->update($user);
        return $result;
    }
    /**
     * Delete by id.
     *
     * @return JsonResponse
    */
    function delete($id): JsonResponse
    {
        $user = new UserDTO();
        $user->setId($id);
        $result = $this->userService->delete($user);
        return $result;
    }
    /**
     * Delete by ids.
     *
     * @return JsonResponse
    */
    function destroy(Request $request): JsonResponse
    {
        $user = new UserDTO();
        $user->setId($request->post('id'));
        $result = $this->userService->destroy($user);
        return $result;
    }
    /**
     * Profil.
     *
     * @return JsonResponse
    */
    function profil(Request $request): JsonResponse
    {
        $user = new UserDTO();
        $user->setToken($request->bearerToken());
        $result = $this->userService->profil($user);
        return $result;
    }
}
