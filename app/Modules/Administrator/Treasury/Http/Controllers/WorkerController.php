<?php

namespace App\Modules\Administrator\Treasury\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Modules\Administrator\Treasury\Services\WorkerService;
use App\Modules\Administrator\Treasury\Http\Requests\Worker\WorkerRequest;
use App\Modules\Administrator\Treasury\Http\Resources\Worker\WorkerDataTableItemResource;
use App\Modules\Administrator\Treasury\Http\Resources\Worker\WorkerPaymentSummaryItemResource;
use App\Modules\Administrator\Treasury\Http\Resources\Worker\WorkerSelectItemResource;

class WorkerController
{
    public function __construct(
        private WorkerService $workerService
    ) {}

    public function dataTable(Request $request)
    {
        $item = $this->workerService->dataTable($request);
        $item['data'] = WorkerDataTableItemResource::collection($item['data']);
        return ApiResponse::success($item);
    }

    public function paymentSummaryDataTable(Request $request)
    {
        $item = $this->workerService->paymentSummaryDataTable($request);
        $item['data'] = WorkerPaymentSummaryItemResource::collection($item['data']);
        return ApiResponse::success($item);
    }

    public function save(WorkerRequest $request)
    {
        $data = $request->validated();
        $this->workerService->save($data);
        return ApiResponse::success(null, 'Trabajador guardado correctamente');
    }

    public function selectAsyncItems(Request $request)
    {
        $item = $this->workerService->selectAsyncItems($request);
        $item = WorkerSelectItemResource::collection($item);
        return ApiResponse::success($item);
    }

    public function paymentCalculation(Request $request, int $workerId)
    {
        $request->validate([
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
        ]);

        $data = $this->workerService->paymentCalculation(
            $workerId,
            $request->period_start,
            $request->period_end,
        );

        return ApiResponse::success($data);
    }

    public function delete(int $id)
    {
        $this->workerService->delete($id);
        return ApiResponse::success(null, 'Trabajador eliminado correctamente');
    }
}
