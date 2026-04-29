<?php

namespace App\Modules\BarbershopPanel\Treasury\Repositories;

use App\Common\Http\Context\AdminContext;
use App\Models\Barbershop\BarberAttendance;
use App\Models\Barbershop\Ticket;
use App\Models\Profile\Barber;
use App\Models\Treasury\EmployeeAdvance;
use App\Models\Treasury\EmployeePayment;
use App\Models\Treasury\Income;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;

class BarberPaymentRepository
{
    public function dataTable($request)
    {
        $branchId = AdminContext::barbershopBranchId();

        $query = EmployeePayment::select('treasury_employee_payments.*')
            ->with([
                'employee' => function (MorphTo $morphTo) {
                    $morphTo->morphWith([Barber::class => ['branch']]);
                },
                'paymentMethod',
                'paidBy',
            ])
            ->join('profile_barbers', 'profile_barbers.id', '=', 'treasury_employee_payments.employee_id')
            ->where('treasury_employee_payments.employee_type', 'profile_barbers')
            ->where('profile_barbers.branch_id', $branchId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('treasury_employee_payments.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function paymentSummaryDataTable($request)
    {
        $branchId = AdminContext::barbershopBranchId();

        $items = Barber::select(
            'profile_barbers.id as id',
            DB::raw("CONCAT(core_persons.name, ' ', COALESCE(core_persons.paternal_surname, ''), ' ', COALESCE(core_persons.maternal_surname, '')) as full_name"),
            'barbershop_branches.name as branch_name',
            'profile_barbers.commission_percentage as commission_percentage',
            DB::raw("(SELECT MAX(ep.payment_date) FROM treasury_employee_payments ep WHERE ep.employee_type = 'profile_barbers' AND ep.employee_id = profile_barbers.id AND ep.status = 'paid') as last_payment_date"),
            DB::raw("(SELECT COALESCE(SUM(ep.total_amount), 0) FROM treasury_employee_payments ep WHERE ep.employee_type = 'profile_barbers' AND ep.employee_id = profile_barbers.id AND ep.status = 'paid') as total_paid"),
        )
            ->join('core_persons', 'profile_barbers.id', '=', 'core_persons.id')
            ->join('barbershop_branches', 'profile_barbers.branch_id', '=', 'barbershop_branches.id')
            ->where('profile_barbers.is_active', true)
            ->where('profile_barbers.branch_id', $branchId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $items->orderBy('core_persons.name', 'asc');
        }

        return $items->dataTable($request);
    }

    public function ensureBelongsToBranch(int $barberId): void
    {
        $branchId = AdminContext::barbershopBranchId();
        $exists = Barber::where('id', $barberId)
            ->where('branch_id', $branchId)
            ->exists();
        if (!$exists) {
            throw new \Exception('El barbero no pertenece a esta sede');
        }
    }

    public function paymentCalculation(int $barberId, string $periodStart, string $periodEnd): array
    {
        $tickets = Ticket::where('profile_barber_id', $barberId)
            ->whereDate('ticket_date', '>=', $periodStart)
            ->whereDate('ticket_date', '<=', $periodEnd)
            ->where('status', 'confirmed')
            ->orderBy('ticket_date', 'asc')
            ->get();

        $ticketsTotal = $tickets->sum('total');
        $ticketsCount = $tickets->count();

        $ticketIds = $tickets->pluck('id');
        $incomes = Income::where('transactionable_type', 'barbershop_tickets')
            ->whereIn('transactionable_id', $ticketIds)
            ->get()
            ->keyBy('transactionable_id');

        $advances = EmployeeAdvance::where('employee_type', 'profile_barbers')
            ->where('employee_id', $barberId)
            ->where('status', 'pending')
            ->whereBetween('advance_date', [$periodStart, $periodEnd])
            ->get();

        $advancesTotal = $advances->sum('amount');

        $attendances = BarberAttendance::where('barber_id', $barberId)
            ->whereBetween('date', [$periodStart, $periodEnd])
            ->orderBy('date', 'asc')
            ->get();

        $absencesCount = $attendances->where('status', 'absent')->count();

        return [
            'tickets' => [
                'count' => $ticketsCount,
                'total' => (float) $ticketsTotal,
                'items' => $tickets->map(function ($t) use ($incomes) {
                    $income = $incomes->get($t->id);
                    return [
                        'id' => $t->id,
                        'ticketDate' => $t->ticket_date->format('Y-m-d'),
                        'amount' => (float) $t->amount,
                        'discount' => (float) $t->discount,
                        'total' => (float) $t->total,
                        'status' => $t->status,
                        'receiptSerie' => $income?->receipt_serie,
                        'receiptNumber' => $income?->receipt_number,
                    ];
                }),
            ],
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