<?php

namespace App\Modules\Administrator\Academy\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Modules\Administrator\Academy\Repositories\TeacherAttendanceRepository;
use App\Models\Academy\TeacherAttendance;
use App\Models\Academy\Group;
use App\Common\Exceptions\ApiException;
use Carbon\Carbon;

class TeacherAttendanceService
{
    public function __construct(
        private TeacherAttendanceRepository $teacherAttendanceRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->teacherAttendanceRepository->dataTable($request);
    }

    public function registerCheckIn(array $data)
    {
        $teacherId = $data['teacher_id'];
        $groupId = $data['group_id'];
        $date = $data['date'] ?? date('Y-m-d');

        $attendance = TeacherAttendance::where('teacher_id', $teacherId)
            ->where('group_id', $groupId)
            ->where('date', $date)
            ->first();

        if ($attendance) {
            if ($attendance->check_in) {
                throw new ApiException('El docente ya tiene entrada registrada para este día', 400);
            }
            $attendance->update([
                'check_in' => date('H:i:s'),
                'status' => $this->resolveCheckInStatus($groupId),
            ]);
        } else {
            TeacherAttendance::create([
                'teacher_id' => $teacherId,
                'group_id' => $groupId,
                'date' => $date,
                'check_in' => date('H:i:s'),
                'status' => $this->resolveCheckInStatus($groupId),
            ]);
        }
    }

    private function resolveCheckInStatus(int $groupId): string
    {
        $group = Group::with('schedule')->find($groupId);

        if (!$group || !$group->schedule || !$group->schedule->start_time) {
            return 'present';
        }

        $now = Carbon::now();
        $tolerance = Carbon::parse($group->schedule->start_time)->addMinutes(15);

        return $now->greaterThan($tolerance) ? 'late' : 'present';
    }

    public function registerCheckOut(array $data)
    {
        $teacherId = $data['teacher_id'];
        $groupId = $data['group_id'];
        $date = $data['date'] ?? date('Y-m-d');

        $attendance = TeacherAttendance::where('teacher_id', $teacherId)
            ->where('group_id', $groupId)
            ->where('date', $date)
            ->first();

        if (!$attendance) {
            TeacherAttendance::create([
                'teacher_id' => $teacherId,
                'group_id' => $groupId,
                'date' => $date,
                'check_out' => date('H:i:s'),
                'status' => 'late',
                'observation' => 'Salida marcada sin entrada registrada'
            ]);
        } else {
            if ($attendance->check_out) {
                throw new ApiException('El docente ya tiene salida registrada para este día', 400);
            }
            $attendance->update([
                'check_out' => date('H:i:s')
            ]);
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
            ->where('group_id', $groupId)
            ->where('date', $date)
            ->first();

        if ($attendance) {
            if ($attendance->status) {
                throw new ApiException('El docente ya tiene un registro de asistencia para este día', 400);
            }
            $attendance->update([
                'status' => 'absent'
            ]);
        } else {
            TeacherAttendance::create([
                'teacher_id' => $teacherId,
                'group_id' => $groupId,
                'date' => $date,
                'status' => 'absent'
            ]);
        }
    }

    public function history(int $teacherId): array
    {
        return TeacherAttendance::where('teacher_id', $teacherId)
            ->orderBy('date', 'desc')
            ->get()
            ->map(fn($a) => [
                'id'          => $a->id,
                'date'        => $a->date->format('Y-m-d'),
                'status'      => $a->status,
                'checkIn'     => $a->check_in,
                'checkOut'    => $a->check_out,
                'observation' => $a->observation,
                'groupId'     => $a->group_id,
            ])
            ->values()
            ->toArray();
    }

    public function generateQrCode(): array
    {
        $timestamp = time();
        $date = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');

        // Crear un código más corto usando base64 + hash
        $secret = config('app.key'); // Usar la clave de la app
        $data = $timestamp . ':' . $date;
        $hash = substr(hash_hmac('sha256', $data, $secret), 0, 16); // 16 caracteres del hash
        $code = base64_encode($timestamp . ':' . $hash);

        return [
            'code' => $code,
            'date' => $date,
            'datetime' => $datetime,
            'timestamp' => $timestamp,
            'expires_at' => date('Y-m-d H:i:s', $timestamp + 300), // 5 minutos de validez
        ];
    }

    public function decodeQrCode(string $code): array
    {
        try {
            $decoded = base64_decode($code);
            $parts = explode(':', $decoded);

            if (count($parts) !== 2) {
                throw new ApiException('Código QR inválido', 400);
            }

            $timestamp = (int) $parts[0];
            $receivedHash = $parts[1];

            // Validar que no haya expirado (5 minutos)
            $now = time();
            if (($now - $timestamp) > 300) {
                throw new ApiException('El código QR ha expirado', 400);
            }

            // Verificar el hash
            $date = date('Y-m-d', $timestamp);
            $secret = config('app.key');
            $data = $timestamp . ':' . $date;
            $expectedHash = substr(hash_hmac('sha256', $data, $secret), 0, 16);

            if (!hash_equals($expectedHash, $receivedHash)) {
                throw new ApiException('Código QR inválido o corrupto', 400);
            }

            return [
                'date' => $date,
                'timestamp' => $timestamp,
                'valid' => true,
            ];
        } catch (\Exception $e) {
            throw new ApiException('Código QR inválido o corrupto', 400);
        }
    }
}
