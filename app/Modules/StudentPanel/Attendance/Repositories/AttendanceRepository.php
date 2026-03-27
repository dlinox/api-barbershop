<?php

namespace App\Modules\StudentPanel\Attendance\Repositories;

use App\Models\Academy\Attendance;

class AttendanceRepository
{
    public function dataTable($request, int $studentId)
    {
        $query = Attendance::select(
            'academy_attendances.id',
            'academy_attendances.check_in',
            'academy_attendances.check_out',
            'academy_attendances.observation',
            'academy_attendances.status',

            // deadline
            'academy_attendances.attendance_deadline_id',
            'academy_attendance_deadlines.date as attendance_deadline_date',

            // group
            'academy_groups.id as group_id',
            'academy_groups.name as group_name',

            // level
            'academy_levels.name as group_level_name',

            // schedule
            'academy_schedules.shift as group_schedule_shift',
            'academy_schedules.start_time as group_schedule_start_time',
            'academy_schedules.end_time as group_schedule_end_time',
        )
            ->join('academy_enrollments', 'academy_attendances.enrollment_id', '=', 'academy_enrollments.id')
            ->join('academy_attendance_deadlines', 'academy_attendances.attendance_deadline_id', '=', 'academy_attendance_deadlines.id')
            ->join('academy_groups', 'academy_attendance_deadlines.group_id', '=', 'academy_groups.id')
            ->join('academy_levels', 'academy_groups.level_id', '=', 'academy_levels.id')
            ->join('academy_schedules', 'academy_groups.schedule_id', '=', 'academy_schedules.id')
            ->where('academy_enrollments.profile_student_id', $studentId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('academy_attendance_deadlines.date', 'desc');
        }

        return $query->dataTable($request, [
            'academy_groups.name',
            'academy_levels.name',
        ]);
    }
}
