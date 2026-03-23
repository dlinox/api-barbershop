<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Models\Academy\Branch;
use App\Models\Academy\GroupTeacher;
use App\Common\Exceptions\ApiException;
use App\Modules\Administrator\Academy\Repositories\GroupRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupService
{
    public function __construct(
        private GroupRepository $groupRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->groupRepository->dataTable($request);
    }

    public function save(array $data)
    {
        try {
            DB::beginTransaction();

            $branch = Branch::select(
                'academy_branches.id',
            )->join('academy_rooms', 'academy_rooms.branch_id', '=', 'academy_branches.id')
                ->where('academy_rooms.id', $data['room_id'])
                ->first();

            $data['branch_id'] = $branch->id;
            $data['days_of_week'] = implode(',', $data['days_of_week']);
            $group = $this->groupRepository->createOrUpdate($data);

            DB::commit();
            return $group;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id)
    {
        return $this->groupRepository->delete($id);
    }

    public function getAvailableEnrollmentGroups(int $studentId)
    {
        return $this->groupRepository->getAvailableEnrollmentGroups($studentId);
    }

    public function selectItems()
    {
        return $this->groupRepository->selectItems();
    }

    public function getActiveAndUpcoming()
    {
        return $this->groupRepository->getActiveAndUpcoming();
    }

    public function assignTeacher(array $data)
    {
        $exists = GroupTeacher::where('group_id', $data['group_id'])
            ->where('teacher_id', $data['teacher_id'])
            ->where('status', 'active')
            ->when(!empty($data['id']), fn($q) => $q->where('id', '!=', $data['id']))
            ->exists();

        if ($exists) {
            throw new ApiException('Este docente ya está asignado como activo en este grupo', 422);
        }

        return $this->groupRepository->assignTeacher($data);
    }
}
