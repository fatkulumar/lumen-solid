<?php

    namespace App\Repositories\Auth\Logout;

    use App\Models\BurnToken;
    use App\Repositories\Eloquent;
    use App\Repositories\Auth\Logout\LogoutRepository;

    class LogoutRepositoryImplement extends Eloquent implements LogoutRepository
    {
        protected $model;

        public function __construct(BurnToken $model)
        {
            $this->model = $model;
        }

        public function logout($token)
        {
            $data = $this->model->create([
                'token' => $token
            ]);

            return $data;
        }
    }