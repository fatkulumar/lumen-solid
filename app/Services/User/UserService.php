<?php

    namespace App\Services\User;

    use App\DataTransferObject\UserDTO;
    use Illuminate\Http\JsonResponse;

    interface UserService
    {
        /**
         * Create a new User Service instance.
         * @return JsonResponse
        */
        public function all(): JsonResponse;
        /**
         * Create a new User Service instance.
         * @param UserDTO
         * @return JsonResponse
        */
        public function getById(UserDTO $params): JsonResponse;
         /**
         * Create a new User Service instance.
         * @param UserDTO
         * @return JsonResponse
        */
        public function create(UserDTO $params): JsonResponse;
        /**
         * Create a new User Service instance.
         * @param UserDTO
         * @return JsonResponse
        */
        public function delete(UserDTO $params): JsonResponse;

        /**
         * Create a new User Service instance.
         * @param UserDTO
         * @return JsonResponse
        */
        public function destroy(UserDTO $params): JsonResponse;
        /**
         * Create a new User Service instance.
         * @param UserDTO
         * @return JsonResponse
        */
        public function update(UserDTO $params): JsonResponse;
        /**
         * Create a new User Service instance.
         * @param UserDTO
         * @return JsonResponse
        */
        public function profil(UserDTO $params): JsonResponse;
    }