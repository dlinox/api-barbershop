<?php

namespace App\Modules\Administrator\Academy\Services;

use Illuminate\Http\Request;
use App\Modules\Administrator\Academy\Repositories\RoomRepository;

class RoomService
{
    public function __construct(
        private RoomRepository $roomRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->roomRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->roomRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->roomRepository->delete($id);
    }

    public function getActiveRooms()
    {
        return $this->roomRepository->getActiveRooms();
    }
}
