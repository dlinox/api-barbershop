<?php

namespace App\Modules\AcademyPanel\Academy\Services;

use Illuminate\Http\Request;
use App\Modules\AcademyPanel\Academy\Repositories\TeacherAttendanceRepository;
use App\Models\Academy\TeacherAttendance;
use App\Models\Academy\Group;
use App\Common\Exceptions\ApiException;
use Carbon\Carbon;

class TeacherAttendanceService
{
    public function __construct(private TeacherAttendanceRepository $teacherAttendanceRepository) {}

    public function dataTable(Request $request) { return $this->teacherAttendanceRepository->dataTable($request); }

    public function registerCheckIn(array $data)
    {
        $teacherId = $data['teacher_id'];
        $groupId = $data['group_id'];
        $date = $data['date'] ?? date('Y-m-d');

        $attendance = TeacherAttendance::where('teacher_id', $teacherId)
            ->where('group_id', $groupId)->where('date', $date)->first();

        if ($attendance) {
            if ($attendance->check_in) throw new ApiException('El docente ya tiene entrada registrada para este día', 400);
            $attendance->update(['check_in' => date('H:i:s'), 'status' => $this->resolveCheckInStatus($groupId)]);
        } else {
            TeacherAttendance::create([
                'teacher_id' => $teacherId, 'group_id' => $groupId, 'date' => $date,
                'check_in' => date('H:i:s'), 'status' => $this->resolveCheckInStatus($groupId),
            ]);
        }
    }

    public function registerCheckOut(array $data)
    {
        $teacherId = $data['teacher_id'];
        $groupId = $data['group_id'];
        $date = $data['date'] ?? date('Y-m-d');

        $attendance = TeacherAttendance::where('teacher_id', $teacherId)
            ->where('group_id', $groupId)->where('date', $date)->first();

        if (!$attendance) {
            TeacherAttendance::create([
                'teacher_id' => $teacherId, 'group_id' => $groupId, 'date' => $date,
                'check_out' => date('H:i:s'), 'status' => 'late', 'observation' => 'Salida marcada sin entrada registrada',
            ]);
        } else {
            if ($attendance->check_out) throw new ApiException('El docente ya tiene salida registrada para este día', 400);
            $attendance->update(['check_out' => date('H:i:s')]);
        }
    }

    public function update(array $data)
    {
        $attendance = TeacherAttendance::findOrFail($data['id']);
        $attendance->update([
            'check_in' => $data['check_in'] ?? $attendance->check_in,
            'check_out' => $data['check_out'] ?? $attendance->check_out,
            'status' => $data['status'] ?? $attendance->status,
            'observation' => $data['observation'] ?? $attendance->observation,
        ]);
        return $attendance;
    }

    public function registerAbsent(array $data)
    {
        $teacherId = $data['teacher_id'];
        $groupId = $data['group_id'];
        $date = $data['date'] ?? date('Y-m-d');

        $attendance = TeacherAttendance::where('teacher_id', $teacherId)
            ->where('group_id', $groupId)->where('date', $date)->first();

        if ($attendance) {
            if ($attendance->status) throw new ApiException('El docente ya tiene un registro de asistencia para este día', 400);
            $attendance->update(['status' => 'absent']);
        } else {
            TeacherAttendance::create([
                'teacher_id' => $teacherId, 'group_id' => $groupId, 'date' => $date, 'status' => 'absent',
            ]);
        }
    }

    public function generateQrCode(): array
    {
        $timestamp = time();
        $date = date('Y-m-d');
        $secret = config('app.key');
        $hash = substr(hash_hmac('sha256', $timestamp . ':' . $date, $secret), 0, 16);
        return [
            'code' => base64_encode($timestamp . ':' . $hash),
            'date' => $date, 'datetime' => date('Y-m-d H:i:s'),
            'timestamp' => $timestamp, 'expires_at' => date('Y-m-d H:i:s', $timestamp + 300),
        ];
    }

    private function resolveCheckInStatus(int $groupId): string
    {
        $group = Group::with('schedule')->find($groupId);
        if (!$group || !$group->schedule || !$group->schedule->start_time) return 'present';
        $now = Carbon::now();
        $tolerance = Carbon::parse($group->schedule->start_time)->addMinutes(15);
        return $now->greaterThan($tolerance) ? 'late' : 'present';
    }
}