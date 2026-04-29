<?php

namespace App\Modules\AcademyPanel\Academy\Services;

use App\Models\Academy\Branch;
use App\Modules\AcademyPanel\Academy\Repositories\GroupRepository;
use Illuminate\Support\Facades\DB;

class GroupService
{
    public function __construct(private GroupRepository $groupRepository) {}

    public function dataTable($request) { return $this->groupRepository->dataTable($request); }

    public function save(array $data)
    {
        try {
            DB::beginTransaction();

            $branch = Branch::select('academy_branches.id')
                ->join('academy_rooms', 'academy_rooms.branch_id', '=', 'academy_branches.id')
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

    public function delete(int $id) { return $this->groupRepository->delete($id); }
    public function cancel(int $id) { return $this->groupRepository->cancel($id); }
    public function assignTeacher(array $data) { return $this->groupRepository->assignTeacher($data); }
    public function checkTeacher(int $groupId, int $teacherId) { return $this->groupRepository->checkTeacher($groupId, $teacherId); }
    public function selectItems() { return $this->groupRepository->selectItems(); }
    public function getAvailableEnrollmentGroups(int $studentId) { return $this->groupRepository->getAvailableEnrollmentGroups($studentId); }
    public function getActiveAndUpcoming() { return $this->groupRepository->getActiveAndUpcoming(); }
}