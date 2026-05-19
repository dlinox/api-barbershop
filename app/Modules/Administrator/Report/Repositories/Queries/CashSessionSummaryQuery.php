<?php

namespace App\Modules\Administrator\Report\Repositories\Queries;

use App\Models\Core\Company;
use App\Models\Treasury\CashSession;
use App\Models\Treasury\Expense;
use App\Models\Treasury\Income;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CashSessionSummaryQuery
{
    public function __invoke(int $cashSessionId): array
    {
        $session = CashSession::with(['cashRegister.infrastructure.infrastructurable', 'openedByUser', 'closedByUser'])
            ->findOrFail($cashSessionId);

        $infrastructureId = $session->cashRegister?->infrastructure_id;
        $infrastructureName = '---';
        $infrastructure = $session->cashRegister?->infrastructure;
        if ($infrastructure) {
            $infrastructureable = $infrastructure->infrastructurable;
            $infrastructureName = $infrastructureable?->name ?? '---';
        }

        // Ingresos agrupados por método de pago
        $paymentSummary = DB::table('treasury_income_payment_methods as ipm')
            ->join('treasury_incomes as i', 'i.id', '=', 'ipm.income_id')
            ->join('core_payment_methods as pm', 'pm.id', '=', 'ipm.payment_method_id')
            ->where('i.cash_session_id', $cashSessionId)
            ->where('i.status', 'completed')
            ->select(
                'pm.name as method_name',
                'pm.type as method_type',
                DB::raw('COUNT(DISTINCT i.id) as income_count'),
                DB::raw('SUM(ipm.amount) as total'),
            )
            ->groupBy('pm.name', 'pm.type')
            ->orderByDesc('total')
            ->get()
            ->toArray();

        $totalIncomes = array_sum(array_column($paymentSummary, 'total'));

        // Egresos
        $expenses = Expense::where('cash_session_id', $cashSessionId)
            ->where('status', 'approved')
            ->with('expenseType')
            ->get();

        $expenseRows = [];
        $totalExpenses = 0;
        foreach ($expenses as $expense) {
            $expenseRows[] = [
                'type'        => $expense->expenseType?->name ?? '---',
                'description' => $expense->description ?? '---',
                'amount'      => (float) $expense->amount,
            ];
            $totalExpenses += (float) $expense->amount;
        }

        // Resumen de tickets (si es barbería)
        $ticketSummary = DB::table('barbershop_tickets')
            ->where('cash_session_id', $cashSessionId)
            ->select(
                'status',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total) as total'),
            )
            ->groupBy('status')
            ->get()
            ->keyBy('status')
            ->toArray();

        return [
            'session'             => $session,
            'infrastructure'      => $infrastructure,
            'infrastructure_name' => $infrastructureName,
            'payment_summary'     => $paymentSummary,
            'total_incomes'       => $totalIncomes,
            'expense_rows'        => $expenseRows,
            'total_expenses'      => $totalExpenses,
            'ticket_summary'      => $ticketSummary,
        ];
    }

    public function toBladeData(array $queryData): array
    {
        $session = $queryData['session'];

        return [
            'company'              => Company::first(),
            'infrastructure'       => $queryData['infrastructure'] ?? null,
            'report_title'         => 'RESUMEN DE CIERRE DE CAJA',
            'report_subtitle'      => 'BARBERÍA',
            'report_date'          => Carbon::parse($session->opened_at)->format('d/m/Y'),
            'report_day'           => $queryData['infrastructure_name'],
            'infrastructure_name'  => $queryData['infrastructure_name'],
            'cash_register_name'   => $session->cashRegister?->name ?? '---',
            'opened_by'            => $session->openedByUser?->username ?? '---',
            'closed_by'            => $session->closedByUser?->username ?? '---',
            'opened_at'            => $session->opened_at ? Carbon::parse($session->opened_at)->format('d/m/Y H:i') : '---',
            'closed_at'            => $session->closed_at ? Carbon::parse($session->closed_at)->format('d/m/Y H:i') : 'Abierta',
            'status'               => $session->status,
            'opening_amount'       => (float) $session->opening_amount,
            'expected_closing'     => (float) $session->expected_closing_amount,
            'actual_closing'       => (float) $session->actual_closing_amount,
            'difference'           => (float) $session->difference,
            'payment_summary'      => $queryData['payment_summary'],
            'total_incomes'        => $queryData['total_incomes'],
            'expense_rows'         => $queryData['expense_rows'],
            'total_expenses'       => $queryData['total_expenses'],
            'ticket_summary'       => $queryData['ticket_summary'],
        ];
    }

    public function toReportData(array $queryData): array
    {
        $session = $queryData['session'];

        return [
            'cash_session_id'     => $session->id,
            'infrastructure_name' => $queryData['infrastructure_name'],
            'cash_register_name'  => $session->cashRegister?->name ?? '---',
            'date'                => $session->opened_at ? Carbon::parse($session->opened_at)->toDateString() : null,
            'opening_amount'      => (float) $session->opening_amount,
            'total_incomes'       => $queryData['total_incomes'],
            'total_expenses'      => $queryData['total_expenses'],
            'expected_closing'    => (float) $session->expected_closing_amount,
            'actual_closing'      => (float) $session->actual_closing_amount,
            'difference'          => (float) $session->difference,
        ];
    }
}
