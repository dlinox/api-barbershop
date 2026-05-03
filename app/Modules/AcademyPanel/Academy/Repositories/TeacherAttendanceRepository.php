<?php

namespace App\Modules\AcademyPanel\Academy\Repositories;

use App\Models\Academy\Group;
use App\Models\Academy\GroupTeacher;
use App\Common\Http\Context\AdminContext;

class TeacherAttendanceRepository
{
    public function dataTable($request)
    {
        $branchId = AdminContext::academyBranchId();
        $date = $request->date ?? date('Y-m-d');
        $dayOfWeek = (int) date('w', strtotime($date)); // 0=Dom, 6=Sáb

        // Paso 1: grupos activos (por status) que tienen clase este día de la semana
        $activeGroupIds = Group::where('status', 'active')
            ->get(['id', 'days_of_week'])
            ->filter(fn($g) => in_array($dayOfWeek, array_map('intval', explode(',', $g->days_of_week))))
            ->pluck('id')
            ->all();

        $query = GroupTeacher::select(
            'academy_group_teachers.id as group_teacher_id', 'academy_group_teachers.teacher_id',
            'academy_group_teachers.group_id', 'academy_group_teachers.hourly_rate',
            'academy_group_teachers.holiday_hourly_rate', 'academy_group_teachers.status as assignment_status',
            'core_persons.id as teacher_person_id', 'core_persons.name as teacher_name',
            'core_persons.paternal_surname as teacher_paternal_surname',
            'core_persons.maternal_surname as teacher_maternal_surname',
            'core_persons.document_number as teacher_document',
            'academy_groups.id as group_id', 'academy_groups.name as group_name',
            'academy_groups.start_date as group_start_date', 'academy_groups.end_date as group_end_date',
            'academy_levels.id as level_id', 'academy_levels.name as level_name',
            'academy_schedules.id as schedule_id', 'academy_schedules.shift as schedule_shift',
            'academy_schedules.start_time as schedule_start_time', 'academy_schedules.end_time as schedule_end_time',
            'academy_teacher_attendances.id as attendance_id',
            'academy_teacher_attendances.date as attendance_date',
            'academy_teacher_attendances.check_in', 'academy_teacher_attendances.check_out',
            'academy_teacher_attendances.check_token', 'academy_teacher_attendances.check_type',
            'academy_teacher_attendances.status as attendance_status', 'academy_teacher_attendances.observation',
        )
            ->join('profile_teachers', 'academy_group_teachers.teacher_id', '=', 'profile_teachers.core_person_id')
            ->join('core_persons', 'profile_teachers.core_person_id', '=', 'core_persons.id')
            ->join('academy_groups', 'academy_group_teachers.group_id', '=', 'academy_groups.id')
            ->join('academy_levels', 'academy_groups.level_id', '=', 'academy_levels.id')
            ->join('academy_schedules', 'academy_groups.schedule_id', '=', 'academy_schedules.id')
            ->leftJoin('academy_teacher_attendances', function ($join) use ($date) {
                $join->on('academy_teacher_attendances.teacher_id', '=', 'academy_group_teachers.teacher_id')
                    ->on('academy_teacher_attendances.group_id', '=', 'academy_group_teachers.group_id')
                    ->where('academy_teacher_attendances.date', '=', $date);
            })
            ->where('academy_group_teachers.status', 'active')
            ->whereIn('academy_groups.id', $activeGroupIds)
            ->where('academy_groups.branch_id', $branchId);

        if (empty($request->sortBy)) { $query->orderBy('core_persons.name', 'asc'); }
        return $query->dataTable($request);
    }
}