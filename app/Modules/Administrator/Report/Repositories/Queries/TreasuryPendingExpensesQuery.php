<?php

namespace App\Modules\Administrator\Report\Repositories\Queries;

use App\Models\Core\Company;
use App\Models\Core\Infrastructure;
use App\Models\Treasury\Expense;
use Carbon\Carbon;

class TreasuryPendingExpensesQuery
{
    public function __invoke(?int $infrastructureId = null): array
    {
        $infrastructure = $infrastructureId ? Infrastructure::find($infrastructureId) : null;

        $expenses = Expense::pending()
            ->when($infrastructureId, fn($q) => $q->where('infrastructure_id', $infrastructureId))
            ->with(['expenseType', 'infrastructure', 'user'])
            ->orderByDesc('transaction_date')
            ->get();

        $rows = [];
        $total = 0;

        foreach ($expenses as $expense) {
            $rows[] = [
                'date'           => Carbon::parse($expense->transaction_date)->format('d/m/Y'),
                'infrastructure' => $expense->infrastructure?->name ?? '---',
                'expense_type'   => $expense->expenseType?->name ?? '---',
                'description'    => $expense->description,
                'voucher_number' => $expense->voucher_number ?? '---',
                'registered_by'  => $expense->user?->username ?? '---',
                'amount'         => (float) $expense->amount,
            ];
            $total += (float) $expense->amount;
        }

        return [
            'infrastructure' => $infrastructure,
            'rows'           => $rows,
            'total'          => $total,
        ];
    }

    public function toBladeData(array $queryData): array
    {
        $now = Carbon::now();
        $dayNames = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        return [
            'company'             => \App\Models\Core\Company::first(),
            'report_title'        => 'GASTOS PENDIENTES DE APROBACIÓN',
            'report_subtitle'     => 'TESORERÍA',
            'report_date'         => $now->format('d/m/Y'),
            'report_day'          => $dayNames[$now->dayOfWeek],
            'infrastructure_name' => $queryData['infrastructure']?->name ?? 'TODAS LAS SEDES',
            'rows'                => $queryData['rows'],
            'total'               => $queryData['total'],
        ];
    }

    public function toReportData(array $queryData): array
    {
        return [
            'infrastructure_id'   => $queryData['infrastructure']?->id,
            'infrastructure_name' => $queryData['infrastructure']?->name ?? 'Todas',
            'total'               => $queryData['total'],
            'rows_count'          => count($queryData['rows']),
        ];
    }
}
