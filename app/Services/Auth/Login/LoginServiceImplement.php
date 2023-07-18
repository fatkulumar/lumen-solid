<?php

    namespace App\Services\Auth\Login;

    use App\DataTransferObject\UserDTO;
    use App\Models\User;
    use App\Repositories\Auth\Login\LoginRepository;
    use App\Services\Auth\Login\LoginService;
    use App\Services\Service;
    use App\Traits\ResultService;
    use App\Traits\EntityValidator;
    use App\Traits\GenerateJWT;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Support\Facades\Hash;

    class LoginServiceImplement extends Service implements LoginService
    {
        use ResultService;
        use EntityValidator;
        use GenerateJWT;

        protected $mainRepository;
        /**
         * Create a new User Service instance.
         * @param LoginRepository
         * @return JsonResponse
        */
        public function __construct(LoginRepository $mainRepository)
        {
            $this->mainRepository = $mainRepository;
        }
        /**
         * Create a new User Service instance.
         * @param UserDTO
         * @return JsonResponse
        */
        public function login(UserDTO $params): JsonResponse
        {
            try {
                $validasiData = $this->authValidator($params);
                if ($this->code != 200) {
                    return $validasiData;
                }

                $email = $params->getEmail();
                $userData = User::where('email', $email)->select('name','email', 'password')->first();
                $cekPassword = Hash::check($params->getPassword(), $userData->password);
                if(!$userData || !$cekPassword) {
                    $this->setResult($email)
                    ->setStatus(false)
                    ->setMessage('Email Tidak Ditemukan')
                    ->setCode(JsonResponse::HTTP_UNAUTHORIZED);
                    return $this->toJson();
                }

                $tokenData = [
                    'email' => $params->getEmail(),
                    'password' => $params->getPassword(),
                ];
                
                $token = $this->generateJwt($tokenData);
                $userData['token'] = $token;
                
                $this->setResult($userData)
                    ->setStatus(true)
                    ->setMessage('Berhasil Login')
                    ->setCode(JsonResponse::HTTP_OK);

            } catch (\Exception $exception) {
                $this->exceptionResponse($exception);
            }
            return $this->toJson();
        }
        /**
         * Create a new User Service instance.
         * @param UserDTO
         * @return JsonResponse
        */
        private function authValidator(UserDTO $params): JsonResponse
        {
            try {
                $rules = [
                    'email' => 'required',
                    'password' => 'required'
                ];
    
                $Validatedata = [
                    'email' => $params->getEmail(),
                    'password' => $params->getPassword()
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