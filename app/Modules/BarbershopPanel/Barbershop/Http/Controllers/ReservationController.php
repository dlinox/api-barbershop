<?php

namespace App\Modules\BarbershopPanel\Barbershop\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Modules\BarbershopPanel\Barbershop\Services\ReservationService;
use App\Modules\Administrator\Barbershop\Http\Requests\Reservation\ReservationRequest;
use App\Modules\Administrator\Barbershop\Http\Resources\Reservation\ReservationDataTableItemResource;

class ReservationController
{
    public function __construct(private ReservationService $reservationService) {}

    public function dataTable(Request $request)
    {
        $items = $this->reservationService->dataTable($request);
        $items['data'] = ReservationDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(ReservationRequest $request)
    {
        $this->reservationService->save($request->validated());
        return ApiResponse::success(null, 'Reservación guardada correctamente');
    }

    public function delete(int $id)
    {
        $this->reservationService->delete($id);
        return ApiResponse::success(null, 'Reservación eliminada correctamente');
    }
}