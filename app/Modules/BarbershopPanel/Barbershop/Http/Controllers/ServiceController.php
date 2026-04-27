<?php

namespace App\Modules\BarbershopPanel\Barbershop\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Modules\BarbershopPanel\Barbershop\Services\ServiceService;
use App\Modules\Administrator\Barbershop\Http\Requests\Service\ServiceRequest;
use App\Modules\Administrator\Barbershop\Http\Resources\Service\ServiceDataTableItemResource;
use App\Modules\Administrator\Barbershop\Http\Resources\Service\ServiceSelectItemResource;
use App\Modules\Administrator\Barbershop\Http\Resources\Service\ServiceSelectItemByInfrastructureResource;

class ServiceController
{
    public function __construct(private ServiceService $serviceService) {}

    public function dataTable(Request $request)
    {
        $items = $this->serviceService->dataTable($request);
        $items['data'] = ServiceDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(ServiceRequest $request)
    {
        $this->serviceService->save($request->validated());
        return ApiResponse::success(null, 'Servicio guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->serviceService->delete($id);
        return ApiResponse::success(null, 'Servicio eliminado correctamente');
    }

    public function selectItems()
    {
        $items = ServiceSelectItemResource::collection($this->serviceService->getActiveServices());
        return ApiResponse::success($items);
    }

    public function getByInfrastructure(int $infrastructureId)
    {
        $items = ServiceSelectItemByInfrastructureResource::collection(
            $this->serviceService->getActiveServicesByInfrastructure($infrastructureId)
        );

        return ApiResponse::success($items);
    }
}
