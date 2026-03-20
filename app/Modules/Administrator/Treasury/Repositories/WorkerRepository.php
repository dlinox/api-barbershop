<?php

namespace App\Modules\Administrator\Treasury\Repositories;

use App\Models\Profile\Worker;
use App\Models\Treasury\EmployeeAdvance;
use App\Models\Treasury\WorkerAttendance;
use Illuminate\Support\Facades\DB;

class WorkerRepository
{
    public function dataTable($request)
    {
        $items = Worker::select(
            'profile_workers.id as id',

            //worker
            'profile_workers.position as position',
            'profile_workers.monthly_salary as monthly_salary',
            'profile_workers.payment_frequency as payment_frequency',
            'profile_workers.is_active as is_active',

            //person
            'core_persons.document_type as person_document_type',
            'core_persons.document_number as person_document_number',
            'core_persons.name as person_name',
            'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname',
            'core_persons.email as person_email',
            'core_persons.phone as person_phone',
        )
            ->join('core_persons', 'profile_workers.id', '=', 'core_persons.id');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $items->orderBy('profile_workers.id', 'desc');
        }

        $items = $items->dataTable($request);
        return $items;
    }

    public function paymentSummaryDataTable($request)
    {
        $items = Worker::select(
            'profile_workers.id as id',
            DB::raw("CONCAT(core_persons.name, ' ', COALESCE(core_persons.paternal_surname, ''), ' ', COALESCE(core_persons.maternal_surname, '')) as full_name"),
            'profile_workers.position as position',
            'profile_workers.monthly_salary as monthly_salary',
            DB::raw("(SELECT MAX(ep.payment_date) FROM treasury_employee_payments ep WHERE ep.employee_type = 'profile_workers' AND ep.employee_id = profile_workers.id AND ep.status = 'paid') as last_payment_date"),
            DB::raw("(SELECT COALESCE(SUM(ep.total_amount), 0) FROM treasury_employee_payments ep WHERE ep.employee_type = 'profile_workers' AND ep.employee_id = profile_workers.id AND ep.status = 'paid') as total_paid"),
        )
            ->join('core_persons', 'profile_workers.id', '=', 'core_persons.id')
            ->where('profile_workers.is_active', true);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $items->orderBy('core_persons.name', 'asc');
        }

        $items = $items->dataTable($request);
        return $items;
    }

    public function findByPersonId(int $personId): ?Worker
    {
        return Worker::where('id', $personId)->first();
    }

    public function create(int $personId, string $position = 'barber', ?float $monthlySalary = null, ?string $paymentFrequency = null, bool $isActive = true): Worker
    {
        return Worker::create([
            'id' => $personId,
            'position' => $position,
            'monthly_salary' => $monthlySalary,
            'payment_frequency' => $paymentFrequency,
            'is_active' => $isActive,
        ]);
    }

    public function update(Worker $worker, string $position, ?float $monthlySalary, ?string $paymentFrequency, bool $isActive): Worker
    {
        $worker->update([
            'position' => $position,
            'monthly_salary' => $monthlySalary,
            'payment_frequency' => $paymentFrequency,
            'is_active' => $isActive,
        ]);
        return $worker;
    }

    public function selectAsyncItems($search)
    {
        $items = Worker::select(
            'profile_workers.id as id',
            'core_persons.name as person_name',
            'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname',
            'core_persons.document_number as person_document_number',
        )
            ->join('core_persons', 'profile_workers.id', '=', 'core_persons.id');

        if (!empty($search)) {
            $items->where(function ($query) use ($search) {
                $query->where('core_persons.name', 'like', "%{$search}%")
                    ->orWhere('core_persons.paternal_surname', 'like', "%{$search}%")
                    ->orWhere('core_persons.maternal_surname', 'like', "%{$search}%")
                    ->orWhere('core_persons.document_number', 'like', "%{$search}%")
                    ->orWhere(DB::raw('CONCAT(core_persons.name, " ", core_persons.paternal_surname, " ", core_persons.maternal_surname)'), 'like', "%{$search}%");
            });
        }

        return $items->limit(20)->get();
    }

    public function paymentCalculation(int $workerId, string $periodStart, string $periodEnd): array
    {
        $advances = EmployeeAdvance::where('employee_type', 'profile_workers')
            ->where('employee_id', $workerId)
            ->where('status', 'pending')
            ->whereBetween('advance_date', [$periodStart, $periodEnd])
            ->get();

        $advancesTotal = $advances->sum('amount');

        $attendances = WorkerAttendance::where('worker_id', $workerId)
            ->whereBetween('date', [$periodStart, $periodEnd])
            ->orderBy('date', 'asc')
            ->get();

        $absencesCount = $attendances->where('status', 'absent')->count();

        return [
            'advances' => [
                'total' => (float) $advancesTotal,
                'items' => $advances->map(fn($a) => [
                    'id' => $a->id,
                    'amount' => (float) $a->amount,
                    'date' => $a->advance_date->format('Y-m-d'),
                    'reason' => $a->reason,
                ]),
            ],
            'absences' => [
                'count' => $absencesCount,
            ],
            'attendances' => [
                'items' => $attendances->map(fn($a) => [
                    'id' => $a->id,
                    'date' => $a->date->format('Y-m-d'),
                    'status' => $a->status,
                    'checkIn' => $a->check_in,
                    'checkOut' => $a->check_out,
                    'observation' => $a->observation,
                ]),
            ],
        ];
    }
}
