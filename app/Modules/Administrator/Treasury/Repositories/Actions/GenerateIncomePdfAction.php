<?php

namespace App\Modules\Administrator\Treasury\Repositories\Actions;

use App\Models\Treasury\Income;
use App\Common\Helpers\PdfHelper;
use App\Common\Helpers\FileHelper;
use App\Models\Core\Company;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Modules\Administrator\Treasury\Repositories\Queries\IncomeDetailQuery;

class GenerateIncomePdfAction
{
    private const PDF_TYPE = 'payment_receipt';

    public function __construct(
        private readonly IncomeDetailQuery $incomeDetailQuery,
    ) {}

    /**
     * Genera el PDF on-the-fly (sin guardar en disco) y devuelve una Response HTTP.
     * Elimina cualquier archivo en disco previamente cacheado para liberar espacio.
     */
    public function execute(int $incomeId): Response
    {
        $income = Income::findOrFail($incomeId);

        // ─── Limpiar archivo en disco si quedó guardado anteriormente ───
        $existingFile = $income->files()->where('type', self::PDF_TYPE)->first();
        if ($existingFile) {
            FileHelper::deleteFile($existingFile->disk, $existingFile->path);
            $existingFile->delete();
        }

        // ─── Generar PDF ───
        $incomeLoaded = ($this->incomeDetailQuery)($incomeId);

        $data = array_merge(
            $this->baseData(),
            $this->incomeDetailQuery->toBladeData($incomeLoaded),
        );

        $headerHtml = View::make('incomes.common.header', $data)->render();
        $footerHtml = View::make('incomes.common.footer', $data)->render();
        $html       = View::make('incomes.receipt', $data)->render();

        $mpdf = PdfHelper::createFromHtml($html, config: [
            'margin_top'    => 35,
            'margin_header' => 8,
            'margin_bottom' => 18,
            'margin_footer' => 8,
        ], headerHtml: $headerHtml, footerHtml: $footerHtml);

        // ─── Marca de agua si está cancelado ───
        if ($income->status === 'cancelled') {
            $mpdf->SetWatermarkText('CANCELADO');
            $mpdf->showWatermarkText  = true;
            $mpdf->watermarkTextAlpha = 0.12;
        }

        $pdfContent    = $mpdf->Output('', 'S');
        $receiptNumber = $income->receipt_serie . '-' . str_pad($income->receipt_number, 8, '0', STR_PAD_LEFT);
        $filename      = "comprobante-{$receiptNumber}.pdf";

        return response($pdfContent, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"{$filename}\"",
            'Cache-Control'       => 'no-store, no-cache, must-revalidate',
            'Pragma'              => 'no-cache',
        ]);
    }

    private function baseData(): array
    {
        return [
            'company'      => Company::first(),
            'generated_by' => Auth::user()?->username ?? 'Sistema',
            'generated_at' => now()->format('d/m/Y H:i'),
        ];
    }
}