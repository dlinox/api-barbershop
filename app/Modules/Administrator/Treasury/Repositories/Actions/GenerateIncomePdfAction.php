<?php

namespace App\Modules\Administrator\Treasury\Repositories\Actions;

use App\Models\Treasury\Income;
use App\Common\Helpers\PdfHelper;
use App\Common\Helpers\FileHelper;
use App\Models\Core\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Modules\Administrator\Treasury\Repositories\Queries\IncomeDetailQuery;

class GenerateIncomePdfAction
{
    private const PDF_TYPE = 'payment_receipt';
    private const PDF_DISK = 'payment_receipts';
    private const PDF_FOLDER = 'receipts';

    public function __construct(
        private readonly IncomeDetailQuery $incomeDetailQuery,
    ) {}

    public function execute(int $incomeId): void
    {
        $income = Income::findOrFail($incomeId);

        // ─── Eliminar PDF anterior si existe ───
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

        $html = View::make('incomes.receipt', $data)->render();
        $mpdf = PdfHelper::createFromHtml($html);

        $pdfContent = $mpdf->Output('', 'S');
        $receiptNumber = $income->receipt_serie . '-' . str_pad($income->receipt_number, 8, '0', STR_PAD_LEFT);
        $filename = FileHelper::generateUniqueFilename("comprobante-{$receiptNumber}", 'pdf');
        $path = self::PDF_FOLDER . '/' . $filename;

        FileHelper::saveFile($pdfContent, $path, self::PDF_DISK);

        $pdfName = "comprobante-{$receiptNumber}.pdf";
        $income->files()->create([
            'type' => self::PDF_TYPE,
            'name' => $pdfName,
            'path' => $path,
            'disk' => self::PDF_DISK,
            'mime_type' => 'application/pdf',
            'size' => strlen($pdfContent),
        ]);
    }

    private function baseData(): array
    {
        return [
            'company' => Company::first(),
            'generated_by' => Auth::user()?->username ?? 'Sistema',
            'generated_at' => now()->format('d/m/Y H:i'),
        ];
    }
}
