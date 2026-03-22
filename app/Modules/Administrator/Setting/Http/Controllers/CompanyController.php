<?php

namespace App\Modules\Administrator\Setting\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Setting\Http\Requests\Company\CompanyRequest;
use App\Modules\Administrator\Setting\Http\Resources\Company\CompanyResource;
use App\Modules\Administrator\Setting\Services\CompanyService;

class CompanyController
{
    public function __construct(
        private CompanyService $companyService
    ) {}

    public function get()
    {
        $company = $this->companyService->get();

        if (!$company) {
            return ApiResponse::success(null);
        }

        return ApiResponse::success(new CompanyResource($company));
    }

    public function save(CompanyRequest $request)
    {
        $data = $request->validated();
        $this->companyService->save($data);
        return ApiResponse::success($data, 'Datos de la empresa guardados correctamente');
    }
}
