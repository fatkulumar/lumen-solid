<?php

    namespace App\DataTransferObject;

    use App\DataTransferObject\GenericDTO;

    class UserDTO extends GenericDTO
    {
        private $id,
                $name,
                $email,
                $file,
                $password;

        public function getId()
        {
            return $this->id;
        }

        public function setId($id)
        {
            return $this->id = $id;
        }

        public function getName()
        {
            return $this->name;
        }

        public function setName($name)
        {
            return $this->name = $name;
        }

        public function getEmail()
        {
            return $this->email;
        }

        public function setEmail($email)
        {
            return $this->email = $email;
        }

        public function getPassword()
        {
            return $this->password;
        }

        public function setPassword($password)
        {
            return $this->password = $password;
        }

        public function getFile()
        {
            return $this->file;
        }

        public function setFile($file)
        {
            return $this->file = $file;
        }
    }

