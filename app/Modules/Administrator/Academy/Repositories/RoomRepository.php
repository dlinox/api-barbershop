<?php

namespace App\Modules\Administrator\Academy\Repositories;

use App\Models\Academy\Room;

class RoomRepository
{
    public function dataTable($request)
    {
        $query = Room::select(
            'academy_rooms.id',
            'academy_rooms.number',
            'academy_rooms.description',
            'academy_rooms.capacity',
            'academy_rooms.floor',
            'academy_rooms.is_active',

            'academy_rooms.branch_id',
            'academy_branches.name as branch_name',
        )
            ->join('academy_branches', 'academy_rooms.branch_id', '=', 'academy_branches.id');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('academy_rooms.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        $room = Room::updateOrCreate(['id' => $data['id']], $data);
        return $room;
    }

    public function delete(int $id)
    {
        $room = Room::find($id);

        if ($room->groups()->exists()) {
            throw new \Exception('No se puede eliminar el aula porque tiene grupos asociados');
        }

        return $room->delete();
    }

    public function getActiveRooms()
    {
        return Room::with('branch')->where('is_active', true)->get();
    }
}
