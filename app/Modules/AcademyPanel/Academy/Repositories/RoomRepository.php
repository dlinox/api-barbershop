<?php

namespace App\Modules\AcademyPanel\Academy\Repositories;

use App\Models\Academy\Room;
use App\Common\Http\Context\AdminContext;

class RoomRepository
{
    public function dataTable($request)
    {
        $branchId = AdminContext::academyBranchId();
        $query = Room::select(
            'academy_rooms.id', 'academy_rooms.number', 'academy_rooms.description',
            'academy_rooms.capacity', 'academy_rooms.floor', 'academy_rooms.is_active',
            'academy_rooms.branch_id', 'academy_branches.name as branch_name',
        )
            ->join('academy_branches', 'academy_rooms.branch_id', '=', 'academy_branches.id')
            ->where('academy_rooms.branch_id', $branchId);
        if (empty($request->sortBy)) { $query->orderBy('academy_rooms.id', 'desc'); }
        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        $data['branch_id'] = AdminContext::academyBranchId();
        return Room::updateOrCreate(['id' => $data['id'] ?? null], $data);
    }

    public function delete(int $id)
    {
        $room = Room::findOrFail($id);
        if ($room->groups()->exists()) throw new \Exception('No se puede eliminar el aula porque tiene grupos asociados');
        return $room->delete();
    }

    public function getActiveRooms()
    {
        $branchId = AdminContext::academyBranchId();
        return Room::with('branch')->where('is_active', true)->where('branch_id', $branchId)->get();
    }
}