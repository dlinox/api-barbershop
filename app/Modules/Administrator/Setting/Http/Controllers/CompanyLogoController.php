<?php

namespace App\Modules\Administrator\Setting\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Setting\Http\Requests\Company\CompanyLogoRequest;
use App\Modules\Administrator\Setting\Services\CompanyLogoService;

class CompanyLogoController
{
    public function __construct(
        private CompanyLogoService $companyLogoService
    ) {}

    public function upload(CompanyLogoRequest $request)
    {
        try {
            $logoUrl = $this->companyLogoService->uploadLogo($request->validated('logo'));
            return ApiResponse::success(['logo' => $logoUrl], 'Logo actualizado correctamente');
        } catch (\InvalidArgumentException $e) {
            return ApiResponse::error($e->getMessage(), null, 422);
        }
    }

    public function delete()
    {
        $this->companyLogoService->deleteLogo();
        return ApiResponse::success(null, 'Logo eliminado correctamente');
    }
}
