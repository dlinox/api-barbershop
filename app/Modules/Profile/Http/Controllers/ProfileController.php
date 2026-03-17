<?php

namespace App\Modules\Profile\Http\Controllers;

use Illuminate\Http\JsonResponse;

use App\Common\Http\Responses\ApiResponse;

use App\Modules\Profile\Services\ProfileService;
use App\Modules\Profile\Http\Requests\UpdatePersonalDataRequest;
use App\Modules\Profile\Http\Requests\UpdateAccountDataRequest;
use App\Modules\Profile\Http\Requests\ChangePasswordRequest;

class ProfileController
{
    public function __construct(private ProfileService $profileService) {}

    public function getProfile(): JsonResponse
    {
        $data = $this->profileService->getProfile();
        return ApiResponse::success($data, '');
    }

    public function updatePersonalData(UpdatePersonalDataRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->profileService->updatePersonalData($data);
        return ApiResponse::success(null, 'Datos personales actualizados correctamente');
    }

    public function updateAccountData(UpdateAccountDataRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->profileService->updateAccountData($data);
        return ApiResponse::success(null, 'Datos de cuenta actualizados correctamente');
    }

    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $data = $request->validated();
        $this->profileService->changePassword($data);
        return ApiResponse::success(null, 'Contraseña actualizada correctamente');
    }
}
