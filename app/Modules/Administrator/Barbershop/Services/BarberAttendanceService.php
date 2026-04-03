<?php

namespace App\Modules\Administrator\Barbershop\Services;

use Illuminate\Http\Request;
use App\Modules\Administrator\Barbershop\Repositories\BarberAttendanceRepository;
use App\Models\Barbershop\BarberAttendance;
use App\Models\Profile\Barber;
use App\Models\Treasury\EmployeeSchedule;
use App\Common\Exceptions\ApiException;

class BarberAttendanceService
{
    public function __construct(
        private BarberAttendanceRepository $barberAttendanceRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->barberAttendanceRepository->dataTable($request);
    }

    public function registerCheckIn(array $data)
    {
        $barberId = $data['barber_id'];
        $date = $data['date'] ?? date('Y-m-d');
        $barber = Barber::findOrFail($barberId);
        $checkIn = date('H:i:s');
        $status = $this->resolveCheckInStatus($checkIn);

        $attendance = BarberAttendance::where('barber_id', $barberId)
            ->where('date', $date)
            ->first();

        if ($attendance) {
            if ($attendance->check_in) {
                throw new ApiException('El barbero ya tiene entrada registrada para este día', 400);
            }
            $attendance->update([
                'check_in' => $checkIn,
                'status' => $status,
            ]);
        } else {
            BarberAttendance::create([
                'branch_id' => $barber->branch_id,
                'barber_id' => $barberId,
                'date' => $date,
                'check_in' => $checkIn,
                'status' => $status,
            ]);
        }
    }

    public function registerCheckOut(array $data)
    {
        $barberId = $data['barber_id'];
        $date = $data['date'] ?? date('Y-m-d');
        $barber = Barber::findOrFail($barberId);

        $attendance = BarberAttendance::where('barber_id', $barberId)
            ->where('date', $date)
            ->first();

        if (!$attendance) {
            BarberAttendance::create([
                'branch_id' => $barber->branch_id,
                'barber_id' => $barberId,
                'date' => $date,
                'check_out' => date('H:i:s'),
                'status' => 'late',
                'observation' => 'Salida marcada sin entrada registrada',
            ]);
        } else {
            if ($attendance->check_out) {
                throw new ApiException('El barbero ya tiene salida registrada para este día', 400);
            }
            $attendance->update([
                'check_out' => date('H:i:s'),
            ]);
        }
    }

    public function update(array $data)
    {
        $attendance = BarberAttendance::findOrFail($data['id']);

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
        $barberId = $data['barber_id'];
        $date = $data['date'] ?? date('Y-m-d');
        $barber = Barber::findOrFail($barberId);

        $attendance = BarberAttendance::where('barber_id', $barberId)
            ->where('date', $date)
            ->first();

        if ($attendance) {
            if ($attendance->status) {
                throw new ApiException('El barbero ya tiene un registro de asistencia para este día', 400);
            }
            $attendance->update([
                'status' => 'absent',
            ]);
        } else {
            BarberAttendance::create([
                'branch_id' => $barber->branch_id,
                'barber_id' => $barberId,
                'date' => $date,
                'status' => 'absent',
            ]);
        }
    }

    public function generateQrCode(): array
    {
        $timestamp = time();
        $date = date('Y-m-d');
        $datetime = date('Y-m-d H:i:s');

        $secret = config('app.key');
        $data = $timestamp . ':' . $date;
        $hash = substr(hash_hmac('sha256', $data, $secret), 0, 16);
        $code = base64_encode($timestamp . ':' . $hash);

        return [
            'code' => $code,
            'date' => $date,
            'datetime' => $datetime,
            'timestamp' => $timestamp,
            'expires_at' => date('Y-m-d H:i:s', $timestamp + 300),
        ];
    }

    private function resolveCheckInStatus(string $checkIn): string
    {
        $schedule = EmployeeSchedule::where('type', 'barber')
            ->where('is_active', true)
            ->first();

        if (!$schedule) {
            return 'present';
        }

        return $checkIn > $schedule->start_time ? 'late' : 'present';
    }
}
