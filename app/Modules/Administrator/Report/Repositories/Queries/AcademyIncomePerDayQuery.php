<?php

namespace App\Modules\Administrator\Report\Repositories\Queries;

use App\Models\Academy\Branch;
use App\Models\Core\Company;
use App\Models\Profile\Worker;
use App\Modules\Administrator\Report\Repositories\ReportAcademyRepository;
use Carbon\Carbon;

class AcademyIncomePerDayQuery
{
    public function __construct(
        private readonly ReportAcademyRepository $repository,
    ) {}

    public function __invoke(int $branchId, string $date, int $workerId): array
    {
        $branch = Branch::findOrFail($branchId);
        $worker = Worker::with('person')->findOrFail($workerId);
        $dailyData = $this->repository->dailyIncome($date, $branchId);

        $workerName = $worker->person?->full_name ?? '---';
        $workerPosition = $worker->position ?? '---';

        $rows = [];
        foreach ($dailyData['rows'] as $index => $row) {
            $rows[] = [
                'number'           => $index + 1,
                'description'      => $row['description'],
                'receipt_type'     => $row['receiptType'],
                'receipt_number'   => $row['serieNumber'],
                'payment_method'   => $row['paymentMethod'],
                'operation_number' => $row['paymentReference'],
                'amount'           => $row['amount'],
            ];
        }

        return [
            'branch'    => $branch,
            'worker_id' => $workerId,
            'worker'    => ['name' => $workerName, 'position' => $workerPosition],
            'rows'      => $rows,
            'totals'    => $dailyData['totals'],
            'date'      => $date,
        ];
    }

    public function toBladeData(array $queryData): array
    {
        $parsedDate = Carbon::parse($queryData['date']);
        $dayNames = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        return [
            'company'           => Company::first(),
            'report_title'      => 'REGISTRO DE INGRESOS DIARIOS',
            'report_subtitle'   => 'ESCUELA',
            'report_date'       => $parsedDate->format('d/m/Y'),
            'report_day'        => $dayNames[$parsedDate->dayOfWeek],
            'branch'          => $queryData['branch'],
            'branch_name'     => $queryData['branch']->name,
            'worker_name'     => $queryData['worker']['name'],
            'worker_position' => $queryData['worker']['position'],
            'rows'            => $queryData['rows'],
            'total_cash'      => $queryData['totals']['cash'],
            'total_bank'      => $queryData['totals']['bank'],
            'total_day'       => $queryData['totals']['total'],
        ];
    }

    public function toReportData(array $queryData): array
    {
        return [
            'branch_id'       => $queryData['branch']->id,
            'branch_name'     => $queryData['branch']->name,
            'worker_id'       => $queryData['worker_id'],
            'worker_name'     => $queryData['worker']['name'],
            'worker_position' => $queryData['worker']['position'],
            'date'            => $queryData['date'],
            'total_cash'      => $queryData['totals']['cash'],
            'total_bank'      => $queryData['totals']['bank'],
            'total_day'       => $queryData['totals']['total'],
            'rows_count'      => count($queryData['rows']),
        ];
    }
}
