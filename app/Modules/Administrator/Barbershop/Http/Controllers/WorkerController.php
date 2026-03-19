<?php

namespace App\Modules\Administrator\Barbershop\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Modules\Administrator\Barbershop\Services\WorkerService;
use App\Modules\Administrator\Barbershop\Http\Requests\Worker\WorkerRequest;
use App\Modules\Administrator\Barbershop\Http\Resources\Worker\WorkerDataTableItemResource;
use App\Modules\Administrator\Barbershop\Http\Resources\Worker\WorkerSelectItemResource;

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

    public function delete(int $id)
    {
        $this->workerService->delete($id);
        return ApiResponse::success(null, 'Trabajador eliminado correctamente');
    }
}
