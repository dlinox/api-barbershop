<?php

namespace App\Modules\Administrator\Profile\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Profile\Services\WorkerService;
use App\Modules\Administrator\Profile\Http\Requests\WorkerRequest;
use App\Modules\Administrator\Profile\Http\Resources\WorkerDataTableItemResource;

class WorkerController
{
    public function __construct(
        private WorkerService $workerService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->workerService->dataTable($request);
        $items['data'] = WorkerDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(WorkerRequest $request)
    {
        $this->workerService->save($request->validated());
        return ApiResponse::success(null, 'Trabajador guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->workerService->delete($id);
        return ApiResponse::success(null, 'Trabajador eliminado correctamente');
    }
}
