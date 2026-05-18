<?php

namespace App\Modules\BarbershopPanel\Barbershop\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Modules\BarbershopPanel\Barbershop\Services\BarberService;
use App\Modules\BarbershopPanel\Barbershop\Http\Requests\BarberRequest;
use App\Modules\BarbershopPanel\Barbershop\Http\Resources\BarberDetailResource;
use App\Modules\Administrator\Barbershop\Http\Resources\Barber\BarberDataTableItemResource;
use App\Modules\Administrator\Barbershop\Http\Resources\Barber\BarberSelectItemResource;

class BarberController
{
    public function __construct(private BarberService $barberService) {}

    public function dataTable(Request $request)
    {
        $item = $this->barberService->dataTable($request);
        $item['data'] = BarberDataTableItemResource::collection($item['data']);
        return ApiResponse::success($item);
    }

    public function save(BarberRequest $request)
    {
        $this->barberService->save($request->validated());
        return ApiResponse::success(null, 'Barbero guardado correctamente');
    }

    public function selectAsyncItems(Request $request)
    {
        $item = BarberSelectItemResource::collection($this->barberService->selectAsyncItems($request));
        return ApiResponse::success($item);
    }

    public function detail(int $id)
    {
        $barber = $this->barberService->detail($id);
        return ApiResponse::success(new BarberDetailResource($barber));
    }

    public function generatePdf(int $id)
    {
        return $this->barberService->generatePdf($id);
    }
}