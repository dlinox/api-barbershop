<?php

namespace App\Modules\Administrator\Academy\Repositories\Actions;

use App\Models\Academy\Enrollment;
use App\Common\Helpers\PdfHelper;
use App\Common\Helpers\FileHelper;
use App\Models\Core\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Modules\Administrator\Academy\Repositories\Queries\EnrollmentDetailQuery;

class GenerateEnrollmentPdfAction
{
    private const PDF_TYPE = 'registration_certificate';
    private const PDF_DISK = 'local';
    private const PDF_FOLDER = 'enrollments';

    public function __construct(
        private readonly EnrollmentDetailQuery $enrollmentDetailQuery,
    ) {}

    public function execute(int $enrollmentId): void
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);

        // ─── Eliminar PDF anterior si existe ───
        $existingFile = $enrollment->files()->where('type', self::PDF_TYPE)->first();

        if ($existingFile) {
            FileHelper::deleteFile($existingFile->disk, $existingFile->path);
            $existingFile->delete();
        }

        // ─── Generar PDF ───
        $enrollmentLoaded = ($this->enrollmentDetailQuery)($enrollmentId);

        $data = array_merge(
            $this->baseData(),
            $this->enrollmentDetailQuery->toBladeData($enrollmentLoaded),
        );

        $html = View::make('enrollments.registration-certificate', $data)->render();
        $mpdf = PdfHelper::createFromHtml($html);

        $pdfContent = $mpdf->Output('', 'S');
        $filename = FileHelper::generateUniqueFilename("ficha-matricula-{$enrollmentId}", 'pdf');
        $path = self::PDF_FOLDER . '/' . $filename;

        FileHelper::saveFile($pdfContent, $path, self::PDF_DISK);

        $enrollment->files()->create([
            'type' => self::PDF_TYPE,
            'name' => "ficha-matricula-{$enrollmentId}.pdf",
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
