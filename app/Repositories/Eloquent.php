<?php

    namespace App\Repositories;

    use App\Models\User;
    use App\Repositories\Repository;

    class Eloquent implements Repository
    {

        public function all(): object
        {
            return $this->model->all();
        }

        public function create($data): User
        {
            return $this->model->create($data);
        }

        public function update($id, array $data): array
        {
            $this->model->findOrFail($id)->update($data);
            return $data;
        }

        public function getById($id): User | null
        {
            $data = $this->model->find($id);
            return $data;
        }

        public function delete($id): bool
        {
            $data = $this->model->find($id)->delete();
            return $data;
        }

        public function destroy(array $id): int
        {
            $data = $this->model->destroy($id);
            return $data;
        }
    }