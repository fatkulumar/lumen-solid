<?php

    namespace App\Services\Auth\Logout;

    use App\DataTransferObject\UserDTO;

    interface LogoutService
    {
        public function logout(UserDTO $params);
    }