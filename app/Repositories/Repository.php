<?php

    namespace App\Repositories;

    interface Repository 
    {
        public function all();
        public function getById($id);
        public function create($data);
        public function update($id, array $data);
        public function delete($id);
        public function destroy(array $id);
    }