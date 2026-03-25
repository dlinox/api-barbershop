<?php

namespace App\Modules\Administrator\Report\Repositories\Queries;

use App\Models\Barbershop\Branch;
use App\Models\Core\Company;
use App\Models\Treasury\Income;
use Carbon\Carbon;

class BarbershopIncomePerDayQuery
{
    public function __invoke(int $branchId, string $date): array
    {
        $branch = Branch::findOrFail($branchId);
        $infrastructureId = $branch->getInfrastructureId();

        $incomes = Income::where('treasury_incomes.status', 'completed')
            ->where('treasury_incomes.transactionable_type', 'barbershop_tickets')
            ->whereDate('treasury_incomes.transaction_date', $date)
            ->where('treasury_incomes.infrastructure_id', $infrastructureId)
            ->with([
                'person',
                'paymentMethods.paymentMethod',
                'transactionable.barber.person',
                'transactionable.services.service',
            ])
            ->orderBy('treasury_incomes.receipt_number')
            ->get();

        $receiptTypeLabels = ['00' => 'Recibo Interno'];
        $rows = [];
        $totalCash = 0;
        $totalBank = 0;

        foreach ($incomes as $income) {
            $ticket = $income->transactionable;
            $barberPerson = $ticket?->barber?->person;
            $barberName = $barberPerson?->full_name ?? '---';
            $clientPerson = $income->person;
            $clientName = $clientPerson?->full_name ?? 'Cliente general';

            $serviceNames = $ticket?->services
                ->map(fn($ts) => $ts->service?->name ?? '---')
                ->implode(', ') ?? '---';

            $description = $serviceNames . ' - ' . $clientName;

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
                'description'      => $description,
                'barber_name'      => $barberName,
                'receipt_type'     => $receiptType,
                'receipt_number'   => $serieNumber,
                'payment_method'   => implode(' / ', $paymentMethodNames),
                'payment_reference' => $paymentReference,
                'amount'           => (float) $income->total,
            ];
        }

        return [
            'branch' => $branch,
            'date'   => $date,
            'rows'   => $rows,
            'totals' => [
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
            'report_subtitle' => 'BARBERÍA',
            'report_date'     => $parsedDate->format('d/m/Y'),
            'report_day'      => $dayNames[$parsedDate->dayOfWeek],
            'branch_name'     => $queryData['branch']->name,
            'rows'            => $queryData['rows'],
            'total_cash'      => $queryData['totals']['cash'],
            'total_bank'      => $queryData['totals']['bank'],
            'total_day'       => $queryData['totals']['total'],
        ];
    }

    public function toReportData(array $queryData): array
    {
        return [
            'branch_id'   => $queryData['branch']->id,
            'branch_name' => $queryData['branch']->name,
            'date'        => $queryData['date'],
            'total_cash'  => $queryData['totals']['cash'],
            'total_bank'  => $queryData['totals']['bank'],
            'total_day'   => $queryData['totals']['total'],
            'rows_count'  => count($queryData['rows']),
        ];
    }
}
