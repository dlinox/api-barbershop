<?php

namespace App\Modules\Administrator\Academy\Repositories;

use App\Models\Academy\Enrollment;
use App\Models\Academy\Group;

use function Laravel\Prompts\select;

class GroupRepository
{
    public function dataTable($request)
    {
        return $this->getGroupsQuery()->dataTable($request);
    }

    public function find(int $id): ?Group
    {
        return Group::find($id);
    }

    public function createOrUpdate(array $data)
    {
        $group = Group::updateOrCreate(['id' => $data['id'] ?? null], $data);
        $group->paymentPlans()->delete();

        if ($group->enrollment_price > 0) {
            $group->paymentPlans()->updateOrCreate(
                [
                    'type' => 'enrollment',
                    'group_id' => $group->id,
                ],
                [
                    'start_date' => $group['start_date'],
                    'end_date' => $group['end_date'],
                    'amount' => $group['enrollment_price'],
                ]
            );
        } else {
            $group->paymentPlans()->where('type', 'enrollment')->delete();
        }

        foreach ($data['payment_plans'] as $paymentPlan) {
            if (!empty($paymentPlan['id'])) {
                $group->paymentPlans()->where('id', $paymentPlan['id'])
                    ->update([
                        'type' => 'monthly',
                        'start_date' => $paymentPlan['start_date'],
                        'end_date' => $paymentPlan['end_date'],
                        'amount' => $paymentPlan['amount'],
                    ]);
            } else {
                $group->paymentPlans()->create([
                    'type' => 'monthly',
                    'start_date' => $paymentPlan['start_date'],
                    'end_date' => $paymentPlan['end_date'],
                    'amount' => $paymentPlan['amount'],
                ]);
            }
        }
        return $group;
    }

    public function delete(int $id)
    {
        $group = Group::find($id);

        $enrollments = Enrollment::where('group_id', $id)->exists();
        if ($enrollments) {
            throw new \Exception('No se puede eliminar el grupo porque tiene inscripciones asociadas');
        }

        $group->paymentPlans()->delete();
        return $group->delete();
    }

    public function getActiveAndUpcomingGroups()
    {
        return $this->getGroupsQuery()
            ->where('academy_groups.end_date', '>', now()) // fecha de fin mayor a la fecha actual
            ->where('academy_groups.is_active', true)
            ->get();
    }


    public function getActiveGroups()
    {
        return $this->getGroupsQuery()
            ->where('academy_groups.start_date', '<=', now()) // fecha de inicio menor o igual a la fecha actual
            ->where('academy_groups.end_date', '>=', now()) // fecha de fin mayor o igual a la fecha actual
            ->where('academy_groups.is_active', true)
            ->get();
    }


    private function getGroupsQuery()
    {
        return Group::select(
            'academy_groups.id',
            'academy_groups.name',
            'academy_groups.start_date',
            'academy_groups.end_date',
            'academy_groups.days_of_week',
            'academy_groups.enrollment_price',
            'academy_groups.monthly_price',
            'academy_groups.is_active',

            'academy_groups.branch_id',
            'academy_branches.name as branch_name',

            'academy_groups.level_id',
            'academy_levels.name as level_name',

            'academy_groups.schedule_id',
            'academy_schedules.shift as schedule_shift',
            'academy_schedules.start_time as schedule_start_time',
            'academy_schedules.end_time as schedule_end_time',

            'academy_groups.room_id',
            'academy_rooms.number as room_number',
            'academy_rooms.floor as room_floor'
        )
            ->join('academy_branches', 'academy_groups.branch_id', '=', 'academy_branches.id')
            ->join('academy_levels', 'academy_groups.level_id', '=', 'academy_levels.id')
            ->join('academy_schedules', 'academy_groups.schedule_id', '=', 'academy_schedules.id')
            ->join('academy_rooms', 'academy_groups.room_id', '=', 'academy_rooms.id');
    }
}
