<?php

namespace App\Modules\TeacherPanel\Attendance\Repositories;

use App\Models\Academy\TeacherAttendance;

class AttendanceRepository
{
    public function dataTable($request, int $teacherId)
    {
        $query = TeacherAttendance::select(
            'academy_teacher_attendances.id',
            'academy_teacher_attendances.date',
            'academy_teacher_attendances.check_in',
            'academy_teacher_attendances.check_out',
            'academy_teacher_attendances.status',
            'academy_teacher_attendances.observation',

            // group
            'academy_groups.id as group_id',
            'academy_groups.name as group_name',

            // schedule
            'academy_schedules.shift as group_schedule_shift',
            'academy_schedules.start_time as group_schedule_start_time',
            'academy_schedules.end_time as group_schedule_end_time',
        )
            ->join('academy_groups', 'academy_teacher_attendances.group_id', '=', 'academy_groups.id')
            ->join('academy_schedules', 'academy_groups.schedule_id', '=', 'academy_schedules.id')
            ->where('academy_teacher_attendances.teacher_id', $teacherId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('academy_teacher_attendances.date', 'desc');
        }

        return $query->dataTable($request, [
            'academy_groups.name',
        ]);
    }
}
