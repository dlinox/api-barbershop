<?php

namespace App\Modules\Administrator\Academy\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Academy\Services\RoomService;
use App\Modules\Administrator\Academy\Http\Requests\Room\RoomRequest;
use App\Modules\Administrator\Academy\Http\Resources\Room\RoomDataTableItemResource;
use App\Modules\Administrator\Academy\Http\Resources\Room\RoomSelectItemResource;

class RoomController
{
    public function __construct(
        private RoomService $roomService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->roomService->dataTable($request);
        $items['data'] = RoomDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(RoomRequest $request)
    {
        $data = $request->validated();
        $this->roomService->save($data);
        return ApiResponse::success(null, 'Aula guardada correctamente');
    }

    public function delete(int $id)
    {
        $this->roomService->delete($id);
        return ApiResponse::success(null, 'Aula eliminada correctamente');
    }

    public function selectItems()
    {
        $items = $this->roomService->getActiveRooms();
        $items = RoomSelectItemResource::collection($items);
        return ApiResponse::success($items);
    }
}
