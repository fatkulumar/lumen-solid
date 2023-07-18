<?php

    namespace App\Repositories\Auth\Login;

    use App\Models\User;
    use App\Repositories\Eloquent;
    use App\Repositories\Auth\Login\LoginRepository;

    class LoginRepositoryImplement extends Eloquent implements LoginRepository
    {
        protected $model;

        public function __construct(User $model)
        {
            $this->model = $model;
        }
    }