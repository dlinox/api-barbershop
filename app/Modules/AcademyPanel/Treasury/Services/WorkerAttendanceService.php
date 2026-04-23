<?php

namespace App\Modules\AcademyPanel\Treasury\Services;

use App\Modules\AcademyPanel\Treasury\Repositories\WorkerAttendanceRepository;
use App\Models\Treasury\WorkerAttendance;
use App\Models\Treasury\EmployeeSchedule;
use App\Common\Exceptions\ApiException;
use Illuminate\Http\Request;

class WorkerAttendanceService
{
    public function __construct(
        private WorkerAttendanceRepository $workerAttendanceRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->workerAttendanceRepository->dataTable($request);
    }

    public function registerCheckIn(array $data)
    {
        $workerId = $data['worker_id'];
        $date = $data['date'] ?? date('Y-m-d');
        $checkIn = date('H:i:s');
        $status = $this->resolveCheckInStatus($checkIn, 'worker');

        $attendance = WorkerAttendance::where('worker_id', $workerId)->where('date', $date)->first();

        if ($attendance) {
            if ($attendance->check_in) {
                throw new ApiException('El trabajador ya tiene entrada registrada para este día', 400);
            }
            $attendance->update(['check_in' => $checkIn, 'status' => $status]);
        } else {
            WorkerAttendance::create(['worker_id' => $workerId, 'date' => $date, 'check_in' => $checkIn, 'status' => $status]);
        }
    }

    public function registerCheckOut(array $data)
    {
        $workerId = $data['worker_id'];
        $date = $data['date'] ?? date('Y-m-d');

        $attendance = WorkerAttendance::where('worker_id', $workerId)->where('date', $date)->first();

        if (!$attendance) {
            WorkerAttendance::create(['worker_id' => $workerId, 'date' => $date, 'check_out' => date('H:i:s'), 'status' => 'late', 'observation' => 'Salida marcada sin entrada registrada']);
        } else {
            if ($attendance->check_out) {
                throw new ApiException('El trabajador ya tiene salida registrada para este día', 400);
            }
            $attendance->update(['check_out' => date('H:i:s')]);
        }
    }

    public function registerAbsent(array $data)
    {
        $workerId = $data['worker_id'];
        $date = $data['date'] ?? date('Y-m-d');

        $attendance = WorkerAttendance::where('worker_id', $workerId)->where('date', $date)->first();

        if ($attendance) {
            if ($attendance->status) {
                throw new ApiException('El trabajador ya tiene un registro de asistencia para este día', 400);
            }
            $attendance->update(['status' => 'absent']);
        } else {
            WorkerAttendance::create(['worker_id' => $workerId, 'date' => $date, 'status' => 'absent']);
        }
    }

    public function update(array $data)
    {
        $attendance = WorkerAttendance::findOrFail($data['id']);
        $attendance->update([
            'check_in'    => $data['check_in'] ?? $attendance->check_in,
            'check_out'   => $data['check_out'] ?? $attendance->check_out,
            'status'      => $data['status'] ?? $attendance->status,
            'observation' => $data['observation'] ?? $attendance->observation,
        ]);
        return $attendance;
    }

    private function resolveCheckInStatus(string $checkIn, string $type): string
    {
        $schedule = EmployeeSchedule::where('type', $type)->where('is_active', true)->first();
        if (!$schedule) return 'present';
        return $checkIn > $schedule->start_time ? 'late' : 'present';
    }
}
