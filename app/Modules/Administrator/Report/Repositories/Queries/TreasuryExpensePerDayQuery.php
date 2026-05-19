<?php

namespace App\Modules\Administrator\Report\Repositories\Queries;

use App\Models\Core\Company;
use App\Models\Core\Infrastructure;
use App\Models\Treasury\Expense;
use Carbon\Carbon;

class TreasuryExpensePerDayQuery
{
    public function __invoke(int $infrastructureId, string $date): array
    {
        $infrastructure = Infrastructure::with('infrastructurable')->findOrFail($infrastructureId);

        $expenses = Expense::approved()
            ->whereDate('transaction_date', $date)
            ->where('infrastructure_id', $infrastructureId)
            ->with(['expenseType', 'paymentMethod', 'user'])
            ->orderBy('id')
            ->get();

        $rows = [];
        $total = 0;

        foreach ($expenses as $expense) {
            $rows[] = [
                'description'     => $expense->description,
                'expense_type'    => $expense->expenseType?->name ?? '---',
                'payment_method'  => $expense->paymentMethod?->name ?? '---',
                'voucher_number'  => $expense->voucher_number ?? '---',
                'registered_by'   => $expense->user?->username ?? '---',
                'amount'          => (float) $expense->amount,
            ];
            $total += (float) $expense->amount;
        }

        return [
            'infrastructure' => $infrastructure,
            'date'           => $date,
            'rows'           => $rows,
            'total'          => $total,
        ];
    }

    public function toBladeData(array $queryData): array
    {
        $parsedDate = Carbon::parse($queryData['date']);
        $dayNames = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        return [
            'company'             => Company::first(),
            'infrastructure'      => $queryData['infrastructure'],
            'report_title'        => 'REGISTRO DE GASTOS DIARIOS',
            'report_subtitle'     => 'TESORERÍA',
            'report_date'         => $parsedDate->format('d/m/Y'),
            'report_day'          => $dayNames[$parsedDate->dayOfWeek],
            'infrastructure_name' => $queryData['infrastructure']->name,
            'rows'                => $queryData['rows'],
            'total'               => $queryData['total'],
        ];
    }

    public function toReportData(array $queryData): array
    {
        return [
            'infrastructure_id'   => $queryData['infrastructure']->id,
            'infrastructure_name' => $queryData['infrastructure']->name,
            'date'                => $queryData['date'],
            'total'               => $queryData['total'],
            'rows_count'          => count($queryData['rows']),
        ];
    }
}
