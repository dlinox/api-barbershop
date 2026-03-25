<?php

namespace App\Modules\Administrator\Academy\Repositories\Actions;

use App\Common\Helpers\FileHelper;
use App\Common\Helpers\PdfHelper;
use App\Models\Core\Company;
use App\Models\Core\Infrastructure;
use App\Models\Profile\Worker;
use App\Models\Reports\Report;
use App\Models\Treasury\Income;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class GenerateAcademyIncomePerDayReportAction
{
    private const REPORT_TYPE = 'academy_income_per_day';
    private const PDF_DISK = 'reports';
    private const PDF_FOLDER = 'academy';
    private const MIN_ROWS = 20;

    public function execute(int $infrastructureId, string $date, int $workerId): Report
    {
        $parsedDate = Carbon::parse($date);

        $infrastructure = Infrastructure::with('infrastructurable')->findOrFail($infrastructureId);
        $branch = $infrastructure->infrastructurable;

        $worker = Worker::with('person')->findOrFail($workerId);

        $incomes = $this->getIncomes($infrastructureId, $parsedDate);

        $reportData = $this->buildReportData($incomes, $branch, $worker, $parsedDate);

        // ─── Referencia única ───
        $reference = self::REPORT_TYPE . ",{$infrastructureId},{$parsedDate->format('Y-m-d')}";

        // ─── Siguiente versión ───
        $version = Report::where('reference', $reference)->max('version') + 1;

        // ─── Generar PDF ───
        $bladeData = array_merge($this->baseData($parsedDate), $reportData);

        $headerHtml = View::make('reports.common.header', $bladeData)->render();
        $footerHtml = View::make('reports.common.footer', $bladeData)->render();
        $html = View::make('reports.academy.income-per-day', $bladeData)->render();

        $mpdf = PdfHelper::createFromHtml($html, config: [
            'margin_top'    => 25,
            'margin_header' => 8,
            'margin_bottom' => 18,
            'margin_footer' => 8,
        ], headerHtml: $headerHtml, footerHtml: $footerHtml);

        $pdfContent = $mpdf->Output('', 'S');
        $filename = FileHelper::generateUniqueFilename(
            "ingreso-diario-academia-{$parsedDate->format('Y-m-d')}-v{$version}",
            'pdf'
        );
        $path = self::PDF_FOLDER . '/' . $filename;

        FileHelper::saveFile($pdfContent, $path, self::PDF_DISK);

        // ─── Crear registro en reports ───
        $report = Report::create([
            'name'      => "Ingresos diarios academia - {$branch->name} - {$parsedDate->format('d/m/Y')}",
            'reference' => $reference,
            'version'   => $version,
            'type'      => self::REPORT_TYPE,
            'data'      => $reportData,
            'file_path' => $path,
        ]);

        return $report;
    }

    private function getIncomes(int $infrastructureId, Carbon $date)
    {
        return Income::with([
            'details',
            'paymentMethods.paymentMethod',
            'person',
        ])
            ->where('infrastructure_id', $infrastructureId)
            ->whereDate('transaction_date', $date)
            ->where('status', '!=', 'annulled')
            ->orderBy('id')
            ->get();
    }

    private function buildReportData($incomes, $branch, $worker, Carbon $date): array
    {
        $rows = [];
        $totalCash = 0;
        $totalBank = 0;

        foreach ($incomes as $index => $income) {
            $description = $this->buildDescription($income);
            $receiptType = $this->getReceiptTypeLabel($income->receipt_type);
            $receiptNumber = $income->receipt_serie . '-' . str_pad($income->receipt_number, 8, '0', STR_PAD_LEFT);

            // Clasificar métodos de pago
            $paymentInfo = $this->classifyPaymentMethods($income);

            $totalCash += $paymentInfo['cash_amount'];
            $totalBank += $paymentInfo['bank_amount'];

            $rows[] = [
                'number'            => $index + 1,
                'description'       => $description,
                'receipt_type'      => $receiptType,
                'receipt_number'    => $receiptNumber,
                'payment_method'    => $paymentInfo['label'],
                'operation_number'  => $paymentInfo['reference'] ?: '-',
                'amount'            => (float) $income->total,
            ];
        }

        $totalDay = $totalCash + $totalBank;

        return [
            'branch_name'     => $branch->name ?? 'N/A',
            'worker_name'     => $worker->person->full_name ?? 'N/A',
            'worker_position' => $worker->position ?? 'N/A',
            'rows'            => $rows,
            'total_cash'      => $totalCash,
            'total_bank'      => $totalBank,
            'total_day'       => $totalDay,
            'report_date_raw' => $date->format('Y-m-d'),
        ];
    }

    private function buildDescription(Income $income): string
    {
        $parts = [];

        foreach ($income->details as $detail) {
            $parts[] = $detail->description;
        }

        $description = implode(', ', $parts);

        if ($income->person) {
            $description .= ' ' . strtoupper($income->person->full_name);
        }

        return $description ?: 'INGRESO';
    }

    private function getReceiptTypeLabel(?string $type): string
    {
        return match (strtolower($type ?? '')) {
            'boleta'  => 'BOLETA',
            'factura' => 'FACTURA',
            'recibo'  => 'RECIBO',
            default   => strtoupper($type ?? '-'),
        };
    }

    private function classifyPaymentMethods(Income $income): array
    {
        $cashAmount = 0;
        $bankAmount = 0;
        $labels = [];
        $references = [];

        foreach ($income->paymentMethods as $pm) {
            $methodName = strtolower($pm->paymentMethod->name ?? '');
            $amount = (float) $pm->amount;

            if ($methodName === 'efectivo') {
                $cashAmount += $amount;
            } else {
                $bankAmount += $amount;
            }

            $labels[] = strtoupper($pm->paymentMethod->name ?? '-');

            if ($pm->payment_reference) {
                $references[] = $pm->payment_reference;
            }
        }

        return [
            'cash_amount' => $cashAmount,
            'bank_amount' => $bankAmount,
            'label'       => implode(' / ', array_unique($labels)) ?: '-',
            'reference'   => implode(', ', $references) ?: null,
        ];
    }

    private function baseData(Carbon $date): array
    {
        return [
            'company'      => Company::first(),
            'report_date'  => $date->format('d/m/Y'),
            'report_day'   => $date->locale('es')->isoFormat('dddd'),
            'generated_by' => Auth::user()?->username ?? 'Sistema',
            'generated_at' => now()->format('d/m/Y H:i'),
        ];
    }
}
