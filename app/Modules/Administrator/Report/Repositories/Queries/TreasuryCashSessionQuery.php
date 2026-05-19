<?php

namespace App\Modules\Administrator\Report\Repositories\Queries;

use App\Models\Core\Company;
use App\Models\Treasury\CashSession;
use Carbon\Carbon;

class TreasuryCashSessionQuery
{
    public function __invoke(int $cashSessionId): array
    {
        $session = CashSession::with(['cashRegister.infrastructure.infrastructurable', 'openedByUser', 'closedByUser'])
            ->findOrFail($cashSessionId);

        $infrastructure = $session->cashRegister?->infrastructure;
        $infraName = $infrastructure?->name ?? '---';
        $registerName = $session->cashRegister?->name ?? '---';

        $incomes = $session->incomes()
            ->where('status', 'completed')
            ->with(['person', 'paymentMethods.paymentMethod'])
            ->orderBy('receipt_number')
            ->get();

        $expenses = $session->expenses()
            ->approved()
            ->with(['expenseType', 'paymentMethod'])
            ->orderBy('id')
            ->get();

        $incomeRows = [];
        $totalIncome = 0;
        foreach ($incomes as $income) {
            $clientName = $income->person?->full_name ?? 'Cliente general';
            $methods = $income->paymentMethods->map(fn($pm) => $pm->paymentMethod?->name ?? '---')->implode(' / ');
            $incomeRows[] = [
                'description'    => $income->observations ?? $clientName,
                'payment_method' => $methods,
                'amount'         => (float) $income->total,
            ];
            $totalIncome += (float) $income->total;
        }

        $expenseRows = [];
        $totalExpense = 0;
        foreach ($expenses as $expense) {
            $expenseRows[] = [
                'description'    => $expense->description,
                'expense_type'   => $expense->expenseType?->name ?? '---',
                'payment_method' => $expense->paymentMethod?->name ?? '---',
                'amount'         => (float) $expense->amount,
            ];
            $totalExpense += (float) $expense->amount;
        }

        return [
            'session'             => $session,
            'infrastructure'      => $infrastructure,
            'infrastructure_name' => $infraName,
            'register_name'       => $registerName,
            'income_rows'         => $incomeRows,
            'expense_rows'        => $expenseRows,
            'total_income'        => $totalIncome,
            'total_expense'       => $totalExpense,
        ];
    }

    public function toBladeData(array $queryData): array
    {
        $session = $queryData['session'];
        $openedAt = $session->opened_at ? Carbon::parse($session->opened_at) : now();
        $dayNames = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        return [
            'company'             => Company::first(),
            'infrastructure'      => $queryData['infrastructure'] ?? null,
            'report_title'        => 'CIERRE DE CAJA',
            'report_subtitle'     => 'TESORERÍA',
            'report_date'         => $openedAt->format('d/m/Y'),
            'report_day'          => $dayNames[$openedAt->dayOfWeek],
            'infrastructure_name' => $queryData['infrastructure_name'],
            'register_name'       => $queryData['register_name'],
            'opened_by'           => $session->openedByUser?->username ?? '---',
            'closed_by'           => $session->closedByUser?->username ?? '---',
            'opened_at'           => $session->opened_at?->format('d/m/Y H:i'),
            'closed_at'           => $session->closed_at?->format('d/m/Y H:i'),
            'opening_amount'      => (float) $session->opening_amount,
            'expected_closing'    => (float) $session->expected_closing_amount,
            'actual_closing'      => (float) $session->actual_closing_amount,
            'difference'          => (float) $session->difference,
            'income_rows'         => $queryData['income_rows'],
            'expense_rows'        => $queryData['expense_rows'],
            'total_income'        => $queryData['total_income'],
            'total_expense'       => $queryData['total_expense'],
            'notes'               => $session->notes,
        ];
    }

    public function toReportData(array $queryData): array
    {
        $session = $queryData['session'];

        return [
            'cash_session_id'     => $session->id,
            'infrastructure_name' => $queryData['infrastructure_name'],
            'register_name'       => $queryData['register_name'],
            'opening_amount'      => (float) $session->opening_amount,
            'total_income'        => $queryData['total_income'],
            'total_expense'       => $queryData['total_expense'],
            'expected_closing'    => (float) $session->expected_closing_amount,
            'actual_closing'      => (float) $session->actual_closing_amount,
            'difference'          => (float) $session->difference,
        ];
    }
}
