<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Models\Academy\Branch;
use App\Modules\Administrator\Academy\Repositories\GroupRepository;
use Illuminate\Http\Request;
use Pest\Plugin\Commands\DumpCommand;

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
        $branch = Branch::select(
            'academy_branches.id',
        )->join('academy_rooms', 'academy_rooms.branch_id', '=', 'academy_branches.id')
            ->where('academy_rooms.id', $data['room_id'])
            ->first();

        $data['branch_id'] = $branch->id;
        $data['days_of_week'] = implode(',', $data['days_of_week']);
        return $this->groupRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->groupRepository->delete($id);
    }

    public function getActiveAndUpcomingGroups()
    {
        return $this->groupRepository->getActiveAndUpcomingGroups();
    }

    public function getActiveGroups()
    {
        return $this->groupRepository->getActiveGroups();
    }
}
