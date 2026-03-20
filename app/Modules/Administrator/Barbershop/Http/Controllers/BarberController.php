<?php

namespace App\Modules\Administrator\Barbershop\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Modules\Administrator\Barbershop\Services\BarberService;
use App\Modules\Administrator\Barbershop\Http\Requests\Barber\BarberRequest;
use App\Modules\Administrator\Barbershop\Http\Resources\Barber\BarberDataTableItemResource;
use App\Modules\Administrator\Barbershop\Http\Resources\Barber\BarberSelectItemResource;
use App\Modules\Administrator\Treasury\Http\Resources\Barber\BarberPaymentSummaryItemResource;

class BarberController
{
    public function __construct(
        private BarberService $barberService
    ) {}

    public function dataTable(Request $request)
    {
        $item = $this->barberService->dataTable($request);
        $item['data'] = BarberDataTableItemResource::collection($item['data']);
        return ApiResponse::success($item);
    }

    public function paymentSummaryDataTable(Request $request)
    {
        $item = $this->barberService->paymentSummaryDataTable($request);
        $item['data'] = BarberPaymentSummaryItemResource::collection($item['data']);
        return ApiResponse::success($item);
    }

    public function save(BarberRequest $request)
    {
        $data = $request->validated();
        $this->barberService->save($data);
        return ApiResponse::success(null, 'Barbero guardado correctamente');
    }

    public function selectAsyncItems(Request $request)
    {
        $item = $this->barberService->selectAsyncItems($request);
        $item = BarberSelectItemResource::collection($item);
        return ApiResponse::success($item);
    }

    public function paymentCalculation(Request $request, int $barberId)
    {
        $request->validate([
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
        ]);

        $data = $this->barberService->paymentCalculation(
            $barberId,
            $request->period_start,
            $request->period_end,
        );

        return ApiResponse::success($data);
    }
}
