<?php

namespace App\Modules\BarbershopPanel\Barbershop\Repositories;

use App\Models\Barbershop\Ticket;
use App\Common\Http\Context\AdminContext;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TicketRepository
{
    public function dataTable(Request $request)
    {
        $branchId = AdminContext::barbershopBranchId();

        $query = Ticket::select(
            'barbershop_tickets.*',
            'core_persons.name as client_name',
            'core_persons.paternal_surname as client_paternal_surname',
            'treasury_incomes.id as income_id',
            'barber_persons.name as barber_name',
            'barber_persons.paternal_surname as barber_paternal_surname',
            'treasury_cash_sessions.status as cash_session_status',
        )
            ->join('treasury_cash_sessions', 'treasury_cash_sessions.id', 'barbershop_tickets.cash_session_id')
            ->join('treasury_cash_registers', 'treasury_cash_registers.id', 'treasury_cash_sessions.cash_register_id')
            ->leftJoin('core_persons', 'core_persons.id', 'barbershop_tickets.profile_client_id')
            ->leftJoin('core_persons as barber_persons', 'barber_persons.id', 'barbershop_tickets.profile_barber_id')
            ->leftJoin('treasury_incomes', function ($join) {
                $join->on('treasury_incomes.transactionable_id', '=', 'barbershop_tickets.id')
                    ->where('treasury_incomes.transactionable_type', '=', 'barbershop_tickets');
            })
            ->where('barbershop_tickets.branch_id', $branchId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('barbershop_tickets.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function findById(int $id): Ticket
    {
        return Ticket::with([
            'services.service.category',
            'sale.items.presentation.product',
        ])->findOrFail($id);
    }

    public function ticketsOverview(int $cashSessionId): array
    {
        $stats = Ticket::selectRaw('status, COUNT(*) as count, COALESCE(SUM(total), 0) as amount')
            ->where('cash_session_id', $cashSessionId)
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $confirmedPayments = DB::table('treasury_income_payment_methods as ipm')
            ->join('treasury_incomes as i', 'i.id', '=', 'ipm.income_id')
            ->join('core_payment_methods as pm', 'pm.id', '=', 'ipm.payment_method_id')
            ->join('barbershop_tickets as t', function ($join) {
                $join->on('i.transactionable_id', '=', 't.id')
                    ->where('i.transactionable_type', '=', 'barbershop_tickets');
            })
            ->where('t.cash_session_id', $cashSessionId)
            ->where('t.status', 'confirmed')
            ->selectRaw("
                COALESCE(SUM(CASE WHEN pm.type = 'cash' THEN ipm.amount ELSE 0 END), 0) as cash_amount,
                COALESCE(SUM(CASE WHEN pm.type != 'cash' THEN ipm.amount ELSE 0 END), 0) as bank_amount
            ")
            ->first();

        return [
            'confirmed' => [
                'count'      => (int)($stats['confirmed']->count ?? 0),
                'amount'     => (float)($stats['confirmed']->amount ?? 0),
                'cashAmount' => (float)($confirmedPayments->cash_amount ?? 0),
                'bankAmount' => (float)($confirmedPayments->bank_amount ?? 0),
            ],
            'pending'   => ['count' => (int)($stats['pending']->count ?? 0),   'amount' => (float)($stats['pending']->amount ?? 0)],
            'cancelled' => ['count' => (int)($stats['cancelled']->count ?? 0), 'amount' => (float)($stats['cancelled']->amount ?? 0)],
            'total'     => ['count' => (int)$stats->sum('count'),               'amount' => (float)$stats->sum('amount')],
        ];
    }

    public function delete(int $id): void
    {
        $ticket = Ticket::findOrFail($id);

        if ($ticket->status !== 'pending') {
            throw new \Exception('Solo se pueden eliminar tickets con estado pendiente.');
        }

        $hasIncome = \App\Models\Treasury\Income::where('transactionable_type', 'barbershop_tickets')
            ->where('transactionable_id', $ticket->id)
            ->exists();

        if ($hasIncome) {
            throw new \Exception('No se puede eliminar un ticket que tiene un ingreso asociado.');
        }

        // Cancel pending sale if exists
        if ($ticket->sale && $ticket->sale->status === 'pending') {
            $ticket->sale->update(['status' => 'cancelled']);
        }

        $ticket->services()->delete();
        $ticket->delete();
    }

    public function waitingQueue(int $cashSessionId): Collection
    {
        return Ticket::select(
            'barbershop_tickets.id',
            'barbershop_tickets.ticket_number',
            'barbershop_tickets.profile_barber_id',
            'barbershop_tickets.total',
            'barbershop_tickets.created_at',
            'core_persons.name as client_name',
            'core_persons.paternal_surname as client_paternal_surname',
            'barber_persons.name as barber_name',
        )
            ->leftJoin('core_persons', 'core_persons.id', 'barbershop_tickets.profile_client_id')
            ->leftJoin('core_persons as barber_persons', 'barber_persons.id', 'barbershop_tickets.profile_barber_id')
            ->where('barbershop_tickets.cash_session_id', $cashSessionId)
            ->where('barbershop_tickets.status', 'pending')
            ->orderBy('barbershop_tickets.created_at', 'asc')
            ->get();
    }
}