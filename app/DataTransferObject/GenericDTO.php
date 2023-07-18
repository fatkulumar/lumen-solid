<?php

    namespace App\DataTransferObject;

    class GenericDTO {
        
        private $token;

        function setToken($token)
        {
            return $this->token = $token;
        }

        function getToken()
        {
            return $this->token;
        }
    }