<?php

namespace App\Modules\Administrator\Barbershop\Http\Controllers;

use Illuminate\Http\Request;

use App\Common\Http\Responses\ApiResponse;

use App\Modules\Administrator\Barbershop\Services\ServiceService;

use App\Modules\Administrator\Barbershop\Http\Requests\Service\ServiceRequest;
use App\Modules\Administrator\Barbershop\Http\Resources\Service\ServiceDataTableItemResource;
use App\Modules\Administrator\Barbershop\Http\Resources\Service\ServiceSelectItemResource;

class ServiceController
{

    public function __construct(
        private ServiceService $serviceService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->serviceService->dataTable($request);
        $items['data'] = ServiceDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(ServiceRequest $request)
    {
        $data = $request->validated();
        $this->serviceService->save($data);
        return ApiResponse::success($data, 'Servicio guardado correctamente');
    }

    public function delete(int $id, int $branchId)
    {
        $this->serviceService->delete($id, $branchId);
        return ApiResponse::success(null, 'Servicio eliminado correctamente');
    }

    public function selectItems()
    {
        $items = $this->serviceService->getActiveServices();
        $items = ServiceSelectItemResource::collection($items);
        return ApiResponse::success($items);
    }
}
