<?php

    namespace App\Repositories\Auth\Logout;

    interface LogoutRepository
    {
        public function logout($token);
    }

