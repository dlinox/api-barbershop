<?php

namespace App\Modules\AcademyPanel\Academy\Repositories;

use App\Models\Academy\Enrollment;
use App\Models\Academy\Group;
use App\Models\Academy\GroupTeacher;
use App\Common\Exceptions\ApiException;
use App\Common\Http\Context\AdminContext;
use Illuminate\Support\Facades\DB;

class GroupRepository
{
    public function dataTable($request)
    {
        $query = $this->getGroupsQuery()
            ->with(['groupTeachers' => function ($q) {
                $q->where('status', 'active')
                    ->join('profile_teachers', 'academy_group_teachers.teacher_id', '=', 'profile_teachers.core_person_id')
                    ->join('core_persons', 'profile_teachers.core_person_id', '=', 'core_persons.id')
                    ->select(
                        'academy_group_teachers.*',
                        'core_persons.name as person_name',
                        'core_persons.paternal_surname as person_paternal_surname',
                        'core_persons.maternal_surname as person_maternal_surname'
                    );
            }])
            ->withCount('enrollments');

        if (empty($request->sortBy)) { $query->orderBy('academy_groups.id', 'desc'); }

        return $query->dataTable($request);
    }

    public function find(int $id): ?Group { return Group::find($id); }

    public function createOrUpdate(array $data)
    {
        $data['branch_id'] = AdminContext::academyBranchId();
        $group = Group::updateOrCreate(['id' => $data['id'] ?? null], $data);

        $newPaymentPlanIds = [];
        foreach ($data['payment_plans'] as $paymentPlan) {
            if (!empty($paymentPlan['id'])) { $newPaymentPlanIds[] = $paymentPlan['id']; }
        }

        $plansToDelete = $group->paymentPlans()->where('type', 'monthly')->whereNotIn('id', $newPaymentPlanIds)->get();
        $plansWithPayments = $plansToDelete->filter(fn($plan) => $plan->enrollmentPaymentDetails()->exists());

        if ($plansWithPayments->isNotEmpty()) {
            throw new ApiException('No se pueden eliminar los planes de pago porque tienen pagos asociados', 422);
        }

        $group->paymentPlans()->where('type', 'monthly')->whereNotIn('id', $newPaymentPlanIds)->delete();

        if ($group->enrollment_price > 0) {
            $group->paymentPlans()->updateOrCreate(
                ['type' => 'enrollment', 'group_id' => $group->id],
                ['start_date' => $group['start_date'], 'end_date' => $group['end_date'], 'amount' => $group['enrollment_price']]
            );
        }

        foreach ($data['payment_plans'] as $paymentPlan) {
            $group->paymentPlans()->updateOrCreate(
                ['id' => $paymentPlan['id'] ?? null],
                ['type' => 'monthly', 'group_id' => $group->id, 'start_date' => $paymentPlan['start_date'], 'end_date' => $paymentPlan['end_date'], 'amount' => $paymentPlan['amount']]
            );
        }

        return $group;
    }

    public function delete(int $id)
    {
        $group = Group::find($id);
        if (Enrollment::where('group_id', $id)->exists()) {
            throw new ApiException('No se puede eliminar el grupo porque tiene inscripciones asociadas', 422);
        }
        $group->paymentPlans()->delete();
        return $group->delete();
    }

    public function getActiveAndUpcoming()
    {
        $branchId = AdminContext::academyBranchId();
        return $this->getGroupsQuery()
            ->where('academy_groups.end_date', '>', now())
            ->where('academy_groups.is_active', true)
            ->where('academy_groups.branch_id', $branchId)
            ->get();
    }

    public function getAvailableEnrollmentGroups(int $studentId)
    {
        $enrollments = Enrollment::where('profile_student_id', $studentId)->get();
        $groupIds = $enrollments->pluck('group_id')->toArray();

        return $this->getGroupsQuery()
            ->distinct()
            ->where('academy_groups.end_date', '>', now())
            ->where('academy_groups.is_active', true)
            ->whereNotIn('academy_groups.id', $groupIds)
            ->orderBy('academy_groups.id', 'desc')
            ->get();
    }

    public function selectItems()
    {
        $branchId = AdminContext::academyBranchId();
        $query = Group::select(
            'academy_groups.id', 'academy_groups.name', 'academy_groups.start_date', 'academy_groups.end_date',
            'academy_groups.days_of_week', 'academy_groups.enrollment_price', 'academy_groups.monthly_price',
            'academy_groups.attendance_tolerance_minutes', 'academy_groups.is_active',
            'academy_groups.branch_id', 'academy_branches.name as branch_name',
            'academy_groups.level_id', 'academy_levels.name as level_name',
        )
            ->join('academy_branches', 'academy_groups.branch_id', '=', 'academy_branches.id')
            ->join('academy_levels', 'academy_groups.level_id', '=', 'academy_levels.id')
            ->where('academy_groups.branch_id', $branchId);

        return $query->get();
    }

    public function assignTeacher(array $data): GroupTeacher
    {
        if (empty($data['id'])) { $data['start_date'] = now()->toDateString(); }
        return GroupTeacher::updateOrCreate(['id' => $data['id'] ?? null], $data);
    }

    public function checkTeacher(int $groupId, int $teacherId): ?GroupTeacher
    {
        return GroupTeacher::where('group_id', $groupId)->where('teacher_id', $teacherId)->where('status', 'active')->first();
    }

    private function getGroupsQuery()
    {
        $branchId = AdminContext::academyBranchId();
        return Group::select(
            'academy_groups.id', 'academy_groups.name', 'academy_groups.start_date', 'academy_groups.end_date',
            'academy_groups.days_of_week', 'academy_groups.enrollment_price', 'academy_groups.monthly_price',
            'academy_groups.attendance_tolerance_minutes', 'academy_groups.is_active',
            'academy_groups.branch_id', 'academy_branches.name as branch_name',
            'academy_groups.level_id', 'academy_levels.name as level_name',
            'academy_groups.schedule_id', 'academy_schedules.shift as schedule_shift',
            'academy_schedules.start_time as schedule_start_time', 'academy_schedules.end_time as schedule_end_time',
            'academy_groups.room_id', 'academy_rooms.number as room_number', 'academy_rooms.floor as room_floor',
        )
            ->join('academy_branches', 'academy_groups.branch_id', '=', 'academy_branches.id')
            ->join('academy_levels', 'academy_groups.level_id', '=', 'academy_levels.id')
            ->join('academy_schedules', 'academy_groups.schedule_id', '=', 'academy_schedules.id')
            ->leftJoin('academy_rooms', 'academy_groups.room_id', '=', 'academy_rooms.id')
            ->where('academy_groups.branch_id', $branchId);
    }
}