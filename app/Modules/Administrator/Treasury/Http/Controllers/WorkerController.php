<?php

namespace App\Modules\Administrator\Treasury\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Modules\Administrator\Treasury\Services\WorkerService;
use App\Modules\Administrator\Treasury\Http\Requests\Worker\WorkerRequest;
use App\Modules\Administrator\Treasury\Http\Resources\Worker\WorkerDataTableItemResource;
use App\Modules\Administrator\Treasury\Http\Resources\Worker\WorkerPaymentSummaryItemResource;
use App\Modules\Administrator\Treasury\Http\Resources\Worker\WorkerSelectItemResource;
use App\Modules\Administrator\Treasury\Repositories\Actions\GenerateWorkerPaymentPdfAction;

class WorkerController
{
    public function __construct(
        private WorkerService $workerService,
        private GenerateWorkerPaymentPdfAction $generateWorkerPaymentPdfAction,
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
        $request->validate(
            [
                'period_start' => 'required|date',
                'period_end'   => 'required|date|after_or_equal:period_start',
            ],
            [
                'period_start.required'     => 'La fecha de inicio es requerida',
                'period_start.date'         => 'La fecha de inicio debe ser una fecha válida',
                'period_end.required'       => 'La fecha de fin es requerida',
                'period_end.date'           => 'La fecha de fin debe ser una fecha válida',
                'period_end.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio',
            ]
        );

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

    public function generateWorkerPaymentPdf(int $id)
    {
        return $this->generateWorkerPaymentPdfAction->execute($id);
    }
}
