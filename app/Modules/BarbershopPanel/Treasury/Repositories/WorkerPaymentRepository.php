<?php

namespace App\Modules\BarbershopPanel\Treasury\Repositories;

use App\Common\Http\Context\AdminContext;
use App\Models\Profile\Worker;
use App\Models\Treasury\EmployeeAdvance;
use App\Models\Treasury\EmployeePayment;
use App\Models\Treasury\WorkerAttendance;
use Illuminate\Support\Facades\DB;

class WorkerPaymentRepository
{
    public function dataTable($request)
    {
        $infrastructureId = AdminContext::infrastructureId();

        $query = EmployeePayment::select('treasury_employee_payments.*')
            ->with(['employee.person', 'paymentMethod', 'paidBy'])
            ->join('profile_workers', 'profile_workers.id', '=', 'treasury_employee_payments.employee_id')
            ->where('treasury_employee_payments.employee_type', 'profile_workers')
            ->where('profile_workers.infrastructure_id', $infrastructureId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('treasury_employee_payments.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function paymentSummaryDataTable($request)
    {
        $infrastructureId = AdminContext::infrastructureId();

        $items = Worker::select(
            'profile_workers.id as id',
            DB::raw("CONCAT(core_persons.name, ' ', COALESCE(core_persons.paternal_surname, ''), ' ', COALESCE(core_persons.maternal_surname, '')) as full_name"),
            'profile_workers.position as position',
            'profile_workers.monthly_salary as monthly_salary',
            DB::raw("(SELECT MAX(ep.payment_date) FROM treasury_employee_payments ep WHERE ep.employee_type = 'profile_workers' AND ep.employee_id = profile_workers.id AND ep.status = 'paid') as last_payment_date"),
            DB::raw("(SELECT COALESCE(SUM(ep.total_amount), 0) FROM treasury_employee_payments ep WHERE ep.employee_type = 'profile_workers' AND ep.employee_id = profile_workers.id AND ep.status = 'paid') as total_paid"),
        )
            ->join('core_persons', 'profile_workers.id', '=', 'core_persons.id')
            ->where('profile_workers.is_active', true)
            ->where('profile_workers.infrastructure_id', $infrastructureId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $items->orderBy('core_persons.name', 'asc');
        }

        return $items->dataTable($request);
    }

    public function ensureBelongsToInfrastructure(int $workerId): void
    {
        $infrastructureId = AdminContext::infrastructureId();
        $exists = Worker::where('id', $workerId)
            ->where('infrastructure_id', $infrastructureId)
            ->exists();
        if (!$exists) {
            throw new \Exception('El trabajador no pertenece a esta infraestructura');
        }
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

    public function createOrUpdate(array $data): EmployeePayment
    {
        if (isset($data['id']) && $data['id']) {
            $payment = EmployeePayment::findOrFail($data['id']);
            if ($payment->status === 'cancelled') {
                throw new \Exception('No se puede editar un pago cancelado');
            }
        }

        $advanceIds = $data['calculation_details']['advance_ids'] ?? [];

        try {
            DB::beginTransaction();
            $payment = EmployeePayment::updateOrCreate(['id' => $data['id'] ?? null], $data);
            if (!empty($advanceIds)) {
                EmployeeAdvance::whereIn('id', $advanceIds)
                    ->where('status', 'pending')
                    ->update(['status' => 'discounted', 'discounted_in_payment_id' => $payment->id]);
            }
            DB::commit();
            return $payment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id): void
    {
        $payment = EmployeePayment::findOrFail($id);
        if ($payment->status === 'cancelled') {
            throw new \Exception('No se puede eliminar un pago cancelado');
        }
        $payment->delete();
    }
}