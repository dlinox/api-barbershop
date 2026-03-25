<?php

namespace App\Modules\Administrator\Report\Repositories\Actions;

use App\Common\Helpers\FileHelper;
use App\Common\Helpers\PdfHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class GenerateReportPdfAction
{
    private const PDF_DISK = 'reports';

    /**
     * @param  string $bodyView      Vista blade del cuerpo (ej: 'reports.academy.income-per-day')
     * @param  string $headerView    Vista blade del header (ej: 'reports.common.header')
     * @param  string $footerView    Vista blade del footer (ej: 'reports.common.footer')
     * @param  array  $data          Datos para las vistas
     * @param  string $folder        Subcarpeta dentro del disco (ej: 'academy')
     * @param  string $filenameBase  Base del nombre del archivo (ej: 'ingreso-diario-academia-2026-03-24')
     * @return string Ruta relativa del archivo guardado
     */
    public function execute(
        string $bodyView,
        string $headerView,
        string $footerView,
        array $data,
        string $folder,
        string $filenameBase,
        string $format = 'a4',
        array $pdfConfig = [],
    ): string {
        $data = array_merge($data, [
            'generated_by' => Auth::user()?->username ?? 'Sistema',
            'generated_at' => now()->format('d/m/Y H:i'),
        ]);

        $headerHtml = View::make($headerView, $data)->render();
        $footerHtml = View::make($footerView, $data)->render();
        $html = View::make($bodyView, $data)->render();

        $defaultConfig = [
            'margin_top'    => 35,
            'margin_header' => 8,
            'margin_bottom' => 18,
            'margin_footer' => 8,
        ];

        $mpdf = PdfHelper::createFromHtml($html, format: $format, config: array_merge($defaultConfig, $pdfConfig), headerHtml: $headerHtml, footerHtml: $footerHtml);

        $pdfContent = $mpdf->Output('', 'S');

        $filename = FileHelper::generateUniqueFilename($filenameBase, 'pdf');
        $path = $folder . '/' . $filename;

        FileHelper::saveFile($pdfContent, $path, self::PDF_DISK);

        return $path;
    }
}
