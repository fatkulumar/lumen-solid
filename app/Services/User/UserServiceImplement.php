<?php

    namespace App\Services\User;

    use App\DataTransferObject\UserDTO;
    use App\Services\User\UserService;
    use App\Repositories\User\UserRepository;
    use App\Services\Service;
    use App\Traits\ResultService;
    use App\Traits\EntityValidator;
    use App\Traits\FileUpload;
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Support\Facades\Hash;

    class UserServiceImplement extends Service implements UserService
    {
        use ResultService;
        use EntityValidator;
        use FileUpload;

        protected $mainRepository;
        /**
         * Create a new User Service instance.
         * @param UserRepository
         * @return JsonResponse
        */
        public function __construct(UserRepository $mainRepository)
        {
            $this->mainRepository = $mainRepository;
        }
        /**
         * Override Settings In FileUpload 
        */
        protected function fileSettings()
        {
            $this->settings = [
                'attributes'  => ['jpg', 'png'],
                'path'        => 'filessss/',
                'softdelete'  => false
            ];
        }
        /**
         * Get All Data Service.
         * 
         * @return void
        */
        public function all(): JsonResponse
        {
            try {
                $data = $this->mainRepository->all();

                $data->map(function ($user) {
                    $user->foto = $user->foto ? app('url')->to('/') . '/storage/' . $user->foto : null;
                    return $user;
                });

                $this->setResult($data)
                    ->setStatus(true)
                    ->setMessage('Data Berhasil Ditemukan')
                    ->setCode(JsonResponse::HTTP_OK);

                return $this->toJson();
            } catch (\Exception $exception) {
                $this->exceptionResponse($exception);
            }
            return $this->toJson();
        }
        /**
         * Get By Id Data Service.
         * 
         * @return void
        */
        public function getById(UserDTO $params): JsonResponse
        {
            try {

                $id = $params->getId();
                $data = $this->mainRepository->getById($id);

                if($data) {
                    collect([$data])->map(function ($user) {
                        $user->foto = $user->foto ? app('url')->to('/') . '/storage/' . $user->foto : null;
                        return $user;
                    });
                }
                        
                $this->setResult($data)
                    ->setStatus(true)
                    ->setMessage('Data Berhasil Ditemukan')
                    ->setCode(JsonResponse::HTTP_OK);

                return $this->toJson();
            } catch (\Exception $exception) {
                $this->exceptionResponse($exception);
            }
            return $this->toJson();
        }
        /**
         * Create User Service.
         * @param UserDTO
         * @return JsonResponse
        */
        public function create(UserDTO $params): JsonResponse
        {
            try {
                $validasiData = $this->createValidator($params);
                if ($this->code != 200) {
                    return $validasiData;
                }

                $file = $params->getFile();
                $this->fileSettings();
                $upload = $this->uploadFile($file);

                $saveData = [
                    'name' => $params->getName(),
                    'email' => $params->getEmail(),
                    'password' => Hash::make($params->getPassword()),
                    'foto' => $upload
                ];

                $data = $this->mainRepository->create($saveData);

                $this->setResult($data)
                ->setStatus(true)
                ->setMessage('Data Berhasil Ditambahkan')
                ->setCode(JsonResponse::HTTP_OK);

            } catch (\Exception $exception) {
                $this->exceptionResponse($exception);
            }
            return $this->toJson();
        }
        /**
         * Update User Service.
         * @param UserDTO
         * @return JsonResponse
        */
        public function update(UserDTO $params): JsonResponse
        {
            try {
                $validasiData = $this->updateValidator($params);
                if ($this->code != 200) {
                    return $validasiData;
                }

                $id = $params->getId();
                $data = $this->mainRepository->getById($id);
                if(!$data) {
                    $this->setResult($id)
                    ->setStatus(false)
                    ->setMessage('Data Tidak Ditemukan')
                    ->setCode(JsonResponse::HTTP_NOT_FOUND);
                    return $this->toJson();
                }

                $file = $params->getFile();
                $this->fileSettings();
                $upload = $this->uploadFile($file);
                $foto = $this->deleteFile($data->foto);

                $updateData = [
                    'name' => $params->getName(),
                    'email' => $params->getEmail(),
                    'password' => $params->getPassword(),
                    'foto' => $upload,
                ];

                $data = $this->mainRepository->update($id, $updateData);

                $this->setResult($updateData)
                    ->setStatus(true)
                    ->setMessage('Data Berhasil Diupdate')
                    ->setCode(JsonResponse::HTTP_OK);
                return $this->toJson();
                
            } catch (\Exception $exception) {
                $this->exceptionResponse($exception);
            }
            return $this->toJson();
        }
        /**
         * Delete User Service.
         * @param UserDTO
         * @return JsonResponse
        */
        public function delete(UserDTO $params): JsonResponse
        {
            try {
                $id = $params->getId();
                $data = $this->mainRepository->getById($id);
                if(!$data) {
                    $this->setResult($id)
                    ->setStatus(false)
                    ->setMessage('Data Tidak Ditemukan')
                    ->setCode(JsonResponse::HTTP_NOT_FOUND);
                    return $this->toJson();
                }

                $this->fileSettings();
                $this->deleteFile($data->foto);
                $this->mainRepository->delete($id);

                $this->setResult($data)
                ->setStatus(true)
                ->setMessage('Data Berhasil Dihapus')
                ->setCode(JsonResponse::HTTP_OK);
            } catch (\Exception $exception) {
                $this->exceptionResponse($exception);
            }
            return $this->toJson();
        }
        /**
         * Destroy User Service.
         * @param UserDTO
         * @return JsonResponse
        */
        public function destroy(UserDTO $params): JsonResponse
        {
            try {
                
                $ids = $params->getId();
                if(count($ids) == 0) {
                    $this->setResult(null)
                    ->setStatus(false)
                    ->setMessage('Tidak Ada Yang Dihapus')
                    ->setCode(JsonResponse::HTTP_OK);
                    return $this->toJson();
                }

                foreach($ids as $item) {
                    $id = $item;
                    $data = $this->mainRepository->getById($id);
                    $this->fileSettings();
                    $this->deleteFile($data->foto);
                    $this->mainRepository->delete($id);
                }
                
                $this->setResult($ids)
                ->setStatus(true)
                ->setMessage('Data Berhasil Dihapus')
                ->setCode(JsonResponse::HTTP_OK);
            } catch (\Exception $exception) {
                $this->exceptionResponse($exception);
            }
            return $this->toJson();
        }
        /**
         * Profil User Service.
         * @param UserDTO
         * @return JsonResponse
        */
        public function profil(UserDTO $params): JsonResponse
        {
            try {
                $token = $params->getToken();
                $decoded = JWT::decode($token, new Key(env('APP_KEY'), env('HASH_JWT')));
                    $this->setResult($decoded)
                    ->setStatus(true)
                    ->setMessage('Berhasil Mendapatkan Profil')
                    ->setCode(JsonResponse::HTTP_OK);
                return $this->toJson();
                
            } catch (\Exception $exception) {
                $this->exceptionResponse($exception);
            }
            return $this->toJson();
        }
        /**
         * Create Validator User Service.
         * @param UserDTO
         * @return JsonResponse
        */
        private function createValidator(UserDTO $params): JsonResponse
        {
            try {
                $rules = [
                    'name' => 'required',
                    'email' => 'required',
                    'password' => 'required',
                    'foto' => 'required'
                ];
    
                $Validatedata = [
                    'name' => $params->getName(),
                    'email' => $params->getEmail(),
                    'password' => $params->getPassword(),
                    'foto' => $params->getFile()
                ];
    
                $validator = EntityValidator::validate($Validatedata, $rules);
                if ($validator->fails()) {
                    $error = $validator->errors();
                    
                    $this->setResult($error)
                    ->setStatus(true)
                    ->setMessage('Gagal melakukan validasi input data')
                    ->setCode(JsonResponse::HTTP_BAD_REQUEST);

                    return $this->toJson();
                }

                $this->setResult(null)
                    ->setStatus(true)
                    ->setMessage('Proses Validasi input data berhasil')
                    ->setCode(JsonResponse::HTTP_OK);
            } catch (\Exception $exception) {
                $this->exceptionResponse($exception);
            }
            return $this->toJson();
        }
        /**
         * Update Validator User Service.
         * @param UserDTO
         * @return JsonResponse
        */
        private function updateValidator(UserDTO $params): JsonResponse
        {
            try {
                $rules = [
                    'id' => 'required',
                    'name' => 'required',
                    'email' => 'required',
                    'password' => 'required',
                    'foto' => 'required'
                ];
    
                $Validatedata = [
                    'id' => $params->getId(),
                    'name' => $params->getName(),
                    'email' => $params->getEmail(),
                    'password' => $params->getPassword(),
                    'foto' => $params->getFile(),
                ];
    
                $validator = EntityValidator::validate($Validatedata, $rules);
                if ($validator->fails()) {
                    $error = $validator->errors();
                    
                    $this->setResult($error)
                    ->setStatus(true)
                    ->setMessage('Gagal melakukan validasi input data')
                    ->setCode(JsonResponse::HTTP_BAD_REQUEST);

                    return $this->toJson();
                }

                $this->setResult(null)
                    ->setStatus(true)
                    ->setMessage('Proses Validasi input data berhasil')
                    ->setCode(JsonResponse::HTTP_OK);
            } catch (\Exception $exception) {
                $this->exceptionResponse($exception);
            }
            return $this->toJson();
        }
    }