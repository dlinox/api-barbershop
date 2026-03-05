<?php

namespace App\Modules\Administrator\Treasury\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Treasury\Services\CashRegisterService;
use App\Modules\Administrator\Treasury\Http\Requests\CashRegister\CashRegisterRequest;
use App\Modules\Administrator\Treasury\Http\Resources\CashRegister\CashRegisterDataTableItemResource;
use App\Modules\Administrator\Treasury\Http\Resources\CashRegister\CashRegisterSelectItemResource;

class CashRegisterController
{
    public function __construct(
        private CashRegisterService $cashRegisterService
    ) {}

    public function dataTable(Request $request, $infrastructureId)
    {
        $items = $this->cashRegisterService->dataTable($request, $infrastructureId);
        $items['data'] = CashRegisterDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(CashRegisterRequest $request)
    {
        $data = $request->validated();
        $this->cashRegisterService->save($data);
        return ApiResponse::success($data, 'Caja guardada correctamente');
    }

    public function delete(int $id)
    {
        $this->cashRegisterService->delete($id);
        return ApiResponse::success(null, 'Caja eliminada correctamente');
    }

    public function selectItems(Request $request, $infrastructureId)
    {
        $items = $this->cashRegisterService->getActiveCashRegisters($infrastructureId);
        $items = CashRegisterSelectItemResource::collection($items);
        return ApiResponse::success($items);
    }
}
