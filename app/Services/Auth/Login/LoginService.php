<?php

    namespace App\Services\Auth\Login;

    use App\DataTransferObject\UserDTO;
    use Illuminate\Http\JsonResponse;

    interface LoginService
    {
        /**
         * Create a new User Service instance.
         * @param UserDTO
         * @return JsonResponse
        */
        public function login(UserDTO $params): JsonResponse;
    }