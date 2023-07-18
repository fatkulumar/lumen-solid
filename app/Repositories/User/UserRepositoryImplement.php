<?php

    namespace App\Repositories\User;

    use App\Models\User;
    use App\Repositories\Eloquent;
    use App\Repositories\User\UserRepository;

    class UserRepositoryImplement extends Eloquent implements UserRepository 
    {
        protected $model;

        public function __construct(User $model)
        {
            $data = $this->model = $model;
        }
    }