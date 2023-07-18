<?php

    namespace App\Services\Auth\Logout;

    use App\DataTransferObject\UserDTO;
    use App\Models\BurnToken;
    use App\Repositories\Auth\Logout\LogoutRepository;
    use App\Services\Auth\Logout\LogoutService;
    use App\Services\Service;
    use App\Traits\ResultService;
    use App\Traits\EntityValidator;
    use App\Traits\GenerateJWT;
    use Carbon\Carbon;
    use DateTime;
    use Illuminate\Http\JsonResponse;

    class LogoutServiceImplement extends Service implements LogoutService
    {
        use ResultService;
        use EntityValidator;
        use GenerateJWT;

        protected $mainRepository;
        public function __construct(LogoutRepository $mainRepository)
        {
            $this->mainRepository = $mainRepository;
        }

        public function logout(UserDTO $params)
        {
            try {
                $this->deleteBurnToken();
                $token = $params->getToken();
                $this->mainRepository->logout($token);
                $this->setResult(null)
                ->setStatus(true)
                ->setMessage('Berhasil Logout')
                ->setCode(JsonResponse::HTTP_OK);
                return $this->toJson();
            } catch (\Exception $exception) {
                $this->exceptionResponse($exception);
            }
            return $this->toJson();
        }

        private function deleteBurnToken() // Delete burn token setelah y hari
        {
            try {
                $selisih_hari = 4;
                $createBurnDate = BurnToken::select('created_at')->get();
                
                foreach($createBurnDate as $item) {
                    $created_at = $item->created_at;
                    $created_at_end = Carbon::parse($created_at)->addDays($selisih_hari);
                    $datetime1 = new DateTime($created_at);
                    $datetime2 = new DateTime($created_at_end);
                    $interval = $datetime1->diff($datetime2);
                    $selisih_hari_burn_token = $interval->d;
                    if($selisih_hari_burn_token == $selisih_hari) {
                        $created_at_sub_days_tujuh = Carbon::parse($created_at)->subDays($selisih_hari);
                        BurnToken::where('created_at', $created_at_sub_days_tujuh)->delete();
                    }
                }
            } catch (\Exception $exception) {
                $this->exceptionResponse($exception);
            }
            return $this->toJson();
        }
    }