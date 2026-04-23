<?php
namespace App\Modules\AcademyPanel\Academy\Http\Controllers;
use App\Common\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Modules\AcademyPanel\Academy\Services\RoomService;
use App\Modules\AcademyPanel\Academy\Http\Requests\RoomRequest;
use App\Modules\Administrator\Academy\Http\Resources\Room\RoomDataTableItemResource;
use App\Modules\Administrator\Academy\Http\Resources\Room\RoomSelectItemResource;
class RoomController {
    public function __construct(private RoomService $roomService) {}
    public function dataTable(Request $request) { $i = $this->roomService->dataTable($request); $i['data'] = RoomDataTableItemResource::collection($i['data']); return ApiResponse::success($i); }
    public function save(RoomRequest $request) { $this->roomService->save($request->validated()); return ApiResponse::success(null, 'Aula guardada correctamente'); }
    public function delete(int $id) { $this->roomService->delete($id); return ApiResponse::success(null, 'Aula eliminada correctamente'); }
    public function selectItems() { return ApiResponse::success(RoomSelectItemResource::collection($this->roomService->getActiveRooms())); }
}