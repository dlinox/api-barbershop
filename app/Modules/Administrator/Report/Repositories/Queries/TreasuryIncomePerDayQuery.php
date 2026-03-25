<?php

namespace App\Modules\Administrator\Report\Repositories\Queries;

use App\Models\Core\Company;
use App\Models\Core\Infrastructure;
use App\Models\Treasury\Income;
use Carbon\Carbon;

class TreasuryIncomePerDayQuery
{
    public function __invoke(int $infrastructureId, string $date): array
    {
        $infrastructure = Infrastructure::findOrFail($infrastructureId);

        $incomes = Income::where('status', 'completed')
            ->whereDate('transaction_date', $date)
            ->where('infrastructure_id', $infrastructureId)
            ->with(['person', 'paymentMethods.paymentMethod'])
            ->orderBy('receipt_number')
            ->get();

        $receiptTypeLabels = ['00' => 'Recibo Interno', '01' => 'Factura', '03' => 'Boleta'];
        $rows = [];
        $totalCash = 0;
        $totalBank = 0;

        foreach ($incomes as $income) {
            $clientName = $income->person?->full_name ?? 'Cliente general';
            $receiptType = $receiptTypeLabels[$income->receipt_type] ?? $income->receipt_type;
            $serieNumber = $income->receipt_serie . '-' . str_pad((string) $income->receipt_number, 8, '0', STR_PAD_LEFT);

            $paymentMethodNames = [];
            $paymentReference = '';
            foreach ($income->paymentMethods as $pm) {
                $methodName = $pm->paymentMethod?->name ?? '---';
                $methodType = $pm->paymentMethod?->type ?? 'cash';
                $paymentMethodNames[] = $methodName;
                if ($pm->payment_reference) {
                    $paymentReference = $pm->payment_reference;
                }
                if ($methodType === 'cash') {
                    $totalCash += (float) $pm->amount;
                } else {
                    $totalBank += (float) $pm->amount;
                }
            }

            $rows[] = [
                'description'       => $income->observations ?? $clientName,
                'client_name'       => $clientName,
                'receipt_type'      => $receiptType,
                'receipt_number'    => $serieNumber,
                'payment_method'    => implode(' / ', $paymentMethodNames),
                'payment_reference' => $paymentReference,
                'amount'            => (float) $income->total,
            ];
        }

        return [
            'infrastructure' => $infrastructure,
            'date'           => $date,
            'rows'           => $rows,
            'totals'         => [
                'cash'  => $totalCash,
                'bank'  => $totalBank,
                'total' => $totalCash + $totalBank,
            ],
        ];
    }

    public function toBladeData(array $queryData): array
    {
        $parsedDate = Carbon::parse($queryData['date']);
        $dayNames = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        return [
            'company'         => Company::first(),
            'report_title'    => 'REGISTRO DE INGRESOS DIARIOS',
            'report_subtitle' => 'TESORERÍA',
            'report_date'     => $parsedDate->format('d/m/Y'),
            'report_day'      => $dayNames[$parsedDate->dayOfWeek],
            'infrastructure_name' => $queryData['infrastructure']->name,
            'rows'            => $queryData['rows'],
            'total_cash'      => $queryData['totals']['cash'],
            'total_bank'      => $queryData['totals']['bank'],
            'total_day'       => $queryData['totals']['total'],
        ];
    }

    public function toReportData(array $queryData): array
    {
        return [
            'infrastructure_id'   => $queryData['infrastructure']->id,
            'infrastructure_name' => $queryData['infrastructure']->name,
            'date'                => $queryData['date'],
            'total_cash'          => $queryData['totals']['cash'],
            'total_bank'          => $queryData['totals']['bank'],
            'total_day'           => $queryData['totals']['total'],
            'rows_count'          => count($queryData['rows']),
        ];
    }
}
