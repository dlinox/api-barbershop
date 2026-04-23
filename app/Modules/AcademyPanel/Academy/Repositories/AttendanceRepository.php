<?php

namespace App\Modules\AcademyPanel\Academy\Repositories;

use App\Models\Academy\Attendance;
use App\Common\Http\Context\AdminContext;

class AttendanceRepository
{
    public function dataTable($request)
    {
        $branchId = AdminContext::academyBranchId();

        $query = Attendance::select(
            'academy_attendances.id', 'academy_attendances.check_in', 'academy_attendances.check_out',
            'academy_attendances.observation', 'academy_attendances.status',
            'academy_attendances.enrollment_id',
            'academy_enrollments.profile_student_id as enrollment_student_id',
            'core_persons.name as enrollment_student_name',
            'core_persons.paternal_surname as enrollment_student_paternal_surname',
            'core_persons.maternal_surname as enrollment_student_maternal_surname',
            'academy_attendances.attendance_deadline_id',
            'academy_attendance_deadlines.date as attendance_deadline_date',
            'academy_groups.id as group_id', 'academy_groups.name as group_name',
            'academy_groups.level_id as group_level_id', 'academy_levels.name as group_level_name',
            'academy_schedules.shift as group_schedule_shift',
            'academy_schedules.start_time as group_schedule_start_time',
            'academy_schedules.end_time as group_schedule_end_time',
        )
            ->join('academy_enrollments', 'academy_attendances.enrollment_id', '=', 'academy_enrollments.id')
            ->join('core_persons', 'academy_enrollments.profile_student_id', '=', 'core_persons.id')
            ->join('academy_attendance_deadlines', 'academy_attendances.attendance_deadline_id', '=', 'academy_attendance_deadlines.id')
            ->join('academy_groups', 'academy_attendance_deadlines.group_id', '=', 'academy_groups.id')
            ->join('academy_levels', 'academy_groups.level_id', '=', 'academy_levels.id')
            ->join('academy_schedules', 'academy_groups.schedule_id', '=', 'academy_schedules.id')
            ->join('academy_branches', 'academy_groups.branch_id', '=', 'academy_branches.id')
            ->where('academy_groups.branch_id', $branchId);

        if (empty($request->sortBy)) { $query->orderBy('academy_attendances.id', 'desc'); }
        return $query->dataTable($request);
    }
}