<?php

namespace App\Modules\TeacherPanel\Group\Repositories;

use App\Models\Academy\Attendance;
use App\Models\Academy\AttendanceDeadline;
use App\Models\Academy\Enrollment;
use App\Models\Academy\Group;
use App\Models\Academy\GroupTeacher;
use App\Common\Http\Responses\ApiException;
use Illuminate\Support\Facades\DB;

class GroupRepository
{
    public function dataTable($request, int $teacherId)
    {
        $query = GroupTeacher::select(
            'academy_group_teachers.id',
            'academy_group_teachers.hourly_rate',
            'academy_group_teachers.status',
            'academy_group_teachers.start_date',
            'academy_group_teachers.end_date',

            // group
            'academy_groups.id as group_id',
            'academy_groups.name as group_name',
            'academy_groups.days_of_week as group_days_of_week',

            // level
            'academy_levels.name as group_level_name',

            // schedule
            'academy_schedules.shift as group_schedule_shift',
            'academy_schedules.start_time as group_schedule_start_time',
            'academy_schedules.end_time as group_schedule_end_time',

            // room & branch
            'academy_rooms.number as group_room_name',
            'academy_branches.name as group_branch_name',
        )
            ->join('academy_groups', 'academy_group_teachers.group_id', '=', 'academy_groups.id')
            ->join('academy_levels', 'academy_groups.level_id', '=', 'academy_levels.id')
            ->join('academy_schedules', 'academy_groups.schedule_id', '=', 'academy_schedules.id')
            ->leftJoin('academy_rooms', 'academy_groups.room_id', '=', 'academy_rooms.id')
            ->leftJoin('academy_branches', 'academy_groups.branch_id', '=', 'academy_branches.id')
            ->where('academy_group_teachers.teacher_id', $teacherId)
            ->selectSub(function ($sub) {
                $sub->selectRaw('COUNT(*)')
                    ->from('academy_enrollments')
                    ->whereColumn('academy_enrollments.group_id', 'academy_groups.id')
                    ->where('academy_enrollments.status', 'active');
            }, 'enrollments_count');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('academy_group_teachers.start_date', 'desc');
        }

        return $query->dataTable($request, [
            'academy_groups.name',
            'academy_levels.name',
            'academy_branches.name',
        ]);
    }

    public function students(int $groupId, int $teacherId)
    {
        $exists = GroupTeacher::where('group_id', $groupId)
            ->where('teacher_id', $teacherId)
            ->exists();

        if (!$exists) {
            return collect();
        }

        return Enrollment::select(
            'academy_enrollments.id as enrollment_id',
            'core_persons.id',
            'core_persons.name',
            'core_persons.paternal_surname',
            'core_persons.maternal_surname',
        )
            ->join('core_persons', 'academy_enrollments.profile_student_id', '=', 'core_persons.id')
            ->where('academy_enrollments.group_id', $groupId)
            ->where('academy_enrollments.status', 'active')
            ->orderBy('core_persons.paternal_surname')
            ->orderBy('core_persons.name')
            ->get();
    }

    public function attendanceDeadlineStatus(int $groupId, string $date, int $teacherId): ?array
    {
        $exists = GroupTeacher::where('group_id', $groupId)
            ->where('teacher_id', $teacherId)
            ->exists();

        if (!$exists) {
            return null;
        }

        $deadline = AttendanceDeadline::where('group_id', $groupId)
            ->where('date', $date)
            ->first();

        if (!$deadline) {
            return null;
        }

        if ($deadline->check_in_deadline && $deadline->check_out_deadline) {
            $status = 'finished';
        } elseif ($deadline->check_in_deadline) {
            $status = 'check-out';
        } else {
            $status = 'check-in';
        }

        return [
            'status'           => $status,
            'checkInDeadline'  => $deadline->check_in_deadline,
            'checkOutDeadline' => $deadline->check_out_deadline,
        ];
    }

    public function startAttendanceDeadline(int $groupId, string $date, int $teacherId): void
    {
        $exists = GroupTeacher::where('group_id', $groupId)
            ->where('teacher_id', $teacherId)
            ->exists();

        if (!$exists) {
            throw new ApiException('No tienes acceso a este grupo', 403);
        }

        $group = Group::findOrFail($groupId);
        $deadline = $group->attendanceDeadlines()->where('date', $date)->first();

        if ($deadline) return;

        try {
            DB::beginTransaction();

            $enrollmentIds = $group->enrollments()
                ->where('status', 'active')
                ->pluck('id')
                ->toArray();

            $deadline = $group->attendanceDeadlines()->create([
                'date' => $date,
            ]);

            foreach ($enrollmentIds as $enrollmentId) {
                Attendance::create([
                    'attendance_deadline_id' => $deadline->id,
                    'enrollment_id'          => $enrollmentId,
                    'status'                 => 'absent',
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage(), 500);
        }
    }

    public function attendances(int $groupId, string $date, int $teacherId)
    {
        $exists = GroupTeacher::where('group_id', $groupId)
            ->where('teacher_id', $teacherId)
            ->exists();

        if (!$exists) {
            return collect();
        }

        $deadline = AttendanceDeadline::where('group_id', $groupId)
            ->where('date', $date)
            ->first();

        if (!$deadline) {
            return collect();
        }

        return Attendance::select(
            'academy_attendances.id',
            'academy_attendances.enrollment_id',
            'academy_attendances.status',
        )
            ->where('academy_attendances.attendance_deadline_id', $deadline->id)
            ->get();
    }

    public function updateAttendanceStatus(int $groupId, int $enrollmentId, string $date, string $status, int $teacherId): void
    {
        $exists = GroupTeacher::where('group_id', $groupId)
            ->where('teacher_id', $teacherId)
            ->exists();

        if (!$exists) {
            throw new ApiException('No tienes acceso a este grupo', 403);
        }

        $deadline = AttendanceDeadline::where('group_id', $groupId)
            ->where('date', $date)
            ->first();

        if (!$deadline) {
            throw new ApiException('No existe un registro de asistencia para esta fecha', 404);
        }

        $attendance = Attendance::where('attendance_deadline_id', $deadline->id)
            ->where('enrollment_id', $enrollmentId)
            ->first();

        if (!$attendance) {
            throw new ApiException('Registro de asistencia no encontrado', 404);
        }

        $attendance->update(['status' => $status]);
    }
}
