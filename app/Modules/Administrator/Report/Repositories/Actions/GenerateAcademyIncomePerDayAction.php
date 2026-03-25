<?php

namespace App\Modules\Administrator\Report\Repositories\Actions;

use App\Common\Helpers\FileHelper;
use App\Common\Helpers\PdfHelper;
use App\Models\Academy\Branch;
use App\Models\Core\Company;
use App\Models\Profile\Worker;
use App\Models\Reports\Report;
use App\Modules\Administrator\Report\Repositories\ReportAcademyRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class GenerateAcademyIncomePerDayAction
{
    private const PDF_DISK = 'reports';
    private const PDF_FOLDER = 'academy';

    public function __construct(
        private readonly ReportAcademyRepository $repository,
    ) {}

    public function execute(int $branchId, string $date, int $workerId): Report
    {
        $branch = Branch::findOrFail($branchId);
        $worker = Worker::with('person')->findOrFail($workerId);
        $dailyData = $this->repository->dailyIncome($date, $branchId);

        $parsedDate = Carbon::parse($date);
        $dayNames = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        $workerName = $worker->person?->full_name ?? '---';
        $workerPosition = $worker->position ?? '---';

        // ─── Preparar filas para el template ───
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

        $company = Company::first();

        $data = [
            'company'         => $company,
            'report_date'     => $parsedDate->format('d/m/Y'),
            'report_day'      => $dayNames[$parsedDate->dayOfWeek],
            'branch_name'     => $branch->name,
            'worker_name'     => $workerName,
            'worker_position' => $workerPosition,
            'rows'            => $rows,
            'total_cash'      => $dailyData['totals']['cash'],
            'total_bank'      => $dailyData['totals']['bank'],
            'total_day'       => $dailyData['totals']['total'],
            'generated_by'    => Auth::user()?->username ?? 'Sistema',
            'generated_at'    => now()->format('d/m/Y H:i'),
        ];

        // ─── Renderizar PDF ───
        $headerHtml = View::make('reports.common.header', $data)->render();
        $footerHtml = View::make('reports.common.footer', $data)->render();
        $html = View::make('reports.academy.income-per-day', $data)->render();

        $mpdf = PdfHelper::createFromHtml($html, config: [
            'margin_top'    => 35,
            'margin_header' => 8,
            'margin_bottom' => 18,
            'margin_footer' => 8,
        ], headerHtml: $headerHtml, footerHtml: $footerHtml);

        $pdfContent = $mpdf->Output('', 'S');

        // ─── Guardar archivo ───
        $filename = FileHelper::generateUniqueFilename("ingreso-diario-academia-{$date}", 'pdf');
        $path = self::PDF_FOLDER . '/' . $filename;

        FileHelper::saveFile($pdfContent, $path, self::PDF_DISK);

        // ─── Crear registro en reports ───
        $reference = "academy_income_per_day,{$branchId},{$date}";

        $existingVersion = Report::where('reference', $reference)->max('version') ?? 0;

        $report = Report::create([
            'name'      => "Ingreso Diario Academia - {$branch->name} - {$date}",
            'reference' => $reference,
            'version'   => $existingVersion + 1,
            'type'      => 'academy_income_per_day',
            'data'      => [
                'branch_id'       => $branchId,
                'branch_name'     => $branch->name,
                'worker_id'       => $workerId,
                'worker_name'     => $workerName,
                'worker_position' => $workerPosition,
                'date'            => $date,
                'total_cash'      => $dailyData['totals']['cash'],
                'total_bank'      => $dailyData['totals']['bank'],
                'total_day'       => $dailyData['totals']['total'],
                'rows_count'      => count($rows),
            ],
            'file_path' => $path,
        ]);

        return $report;
    }
}
