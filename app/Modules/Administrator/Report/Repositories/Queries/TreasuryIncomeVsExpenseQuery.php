<?php

namespace App\Modules\Administrator\Report\Repositories\Queries;

use App\Models\Core\Company;
use App\Models\Core\Infrastructure;
use App\Models\Treasury\Expense;
use App\Models\Treasury\Income;
use Carbon\Carbon;

class TreasuryIncomeVsExpenseQuery
{
    public function __invoke(int $infrastructureId, string $dateFrom, string $dateTo): array
    {
        $infrastructure = Infrastructure::findOrFail($infrastructureId);
        $from = $dateFrom;
        $to = $dateTo . ' 23:59:59';

        $incomes = Income::where('status', 'completed')
            ->whereBetween('transaction_date', [$from, $to])
            ->where('infrastructure_id', $infrastructureId)
            ->with(['paymentMethods.paymentMethod'])
            ->orderBy('transaction_date')
            ->get();

        $expenses = Expense::approved()
            ->whereBetween('transaction_date', [$from, $to])
            ->where('infrastructure_id', $infrastructureId)
            ->with(['expenseType'])
            ->orderBy('transaction_date')
            ->get();

        $totalIncome = (float) $incomes->sum('total');
        $totalExpense = (float) $expenses->sum('amount');

        // Group incomes by day
        $incomeByDay = $incomes->groupBy(fn($i) => Carbon::parse($i->transaction_date)->toDateString());
        $expenseByDay = $expenses->groupBy(fn($e) => Carbon::parse($e->transaction_date)->toDateString());

        $dayRows = [];
        $start = Carbon::parse($dateFrom);
        $end = Carbon::parse($dateTo);

        while ($start->lte($end)) {
            $dateStr = $start->toDateString();
            $dayIncome = isset($incomeByDay[$dateStr]) ? (float) $incomeByDay[$dateStr]->sum('total') : 0;
            $dayExpense = isset($expenseByDay[$dateStr]) ? (float) $expenseByDay[$dateStr]->sum('amount') : 0;

            $dayRows[] = [
                'date'    => $start->format('d/m/Y'),
                'income'  => $dayIncome,
                'expense' => $dayExpense,
                'net'     => $dayIncome - $dayExpense,
            ];
            $start->addDay();
        }

        // Expense breakdown by type
        $expenseByType = $expenses->groupBy(fn($e) => $e->expenseType?->name ?? 'Sin tipo')
            ->map(fn($group, $type) => [
                'type'   => $type,
                'amount' => (float) $group->sum('amount'),
                'count'  => $group->count(),
            ])->values()->toArray();

        return [
            'infrastructure' => $infrastructure,
            'date_from'      => $dateFrom,
            'date_to'        => $dateTo,
            'day_rows'       => $dayRows,
            'expense_by_type' => $expenseByType,
            'total_income'   => $totalIncome,
            'total_expense'  => $totalExpense,
            'net_profit'     => $totalIncome - $totalExpense,
        ];
    }

    public function toBladeData(array $queryData): array
    {
        $parsedFrom = Carbon::parse($queryData['date_from']);
        $parsedTo = Carbon::parse($queryData['date_to']);

        return [
            'company'             => Company::first(),
            'report_title'        => 'INGRESOS VS GASTOS',
            'report_subtitle'     => 'TESORERÍA',
            'report_date'         => $parsedFrom->format('d/m/Y') . ' - ' . $parsedTo->format('d/m/Y'),
            'report_day'          => $parsedFrom->format('d/m/Y') . ' al ' . $parsedTo->format('d/m/Y'),
            'infrastructure_name' => $queryData['infrastructure']->name,
            'day_rows'            => $queryData['day_rows'],
            'expense_by_type'     => $queryData['expense_by_type'],
            'total_income'        => $queryData['total_income'],
            'total_expense'       => $queryData['total_expense'],
            'net_profit'          => $queryData['net_profit'],
        ];
    }

    public function toReportData(array $queryData): array
    {
        return [
            'infrastructure_id'   => $queryData['infrastructure']->id,
            'infrastructure_name' => $queryData['infrastructure']->name,
            'date_from'           => $queryData['date_from'],
            'date_to'             => $queryData['date_to'],
            'total_income'        => $queryData['total_income'],
            'total_expense'       => $queryData['total_expense'],
            'net_profit'          => $queryData['net_profit'],
        ];
    }
}
