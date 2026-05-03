<?php

namespace App\Modules\Administrator\Treasury\Repositories\Actions;

use App\Models\Treasury\EmployeePayment;
use App\Models\Profile\Worker;
use App\Models\Core\Company;
use App\Models\Core\Infrastructure;
use App\Common\Helpers\PdfHelper;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class GenerateWorkerPaymentPdfAction
{
    public function execute(int $paymentId): Response
    {
        $payment = EmployeePayment::with(['paymentMethod', 'paidBy'])->findOrFail($paymentId);

        $worker = Worker::with('person')->find($payment->employee_id);

        $infra = Infrastructure::with('infrastructurable')->find($payment->infrastructure_id ?? $worker?->infrastructure_id);
        $infrastructure = $infra?->infrastructurable;

        $company = Company::first();

        $calculationDetails = $payment->calculation_details ?? [];

        $data = [
            'company'             => $company,
            'payment'             => $payment,
            'worker'              => $worker,
            'infrastructure'      => $infrastructure,
            'calculation_details' => $calculationDetails,
            'generated_by'        => Auth::user()?->username ?? 'Sistema',
            'generated_at'        => now()->format('d/m/Y H:i'),
        ];

        $headerHtml = View::make('payments.common.header', $data)->render();
        $footerHtml = View::make('payments.common.footer', $data)->render();
        $html       = View::make('payments.worker-payment-voucher', $data)->render();

        $mpdf = PdfHelper::createFromHtml($html, PdfHelper::A5, config: [
            'margin_top'    => 38,
            'margin_header' => 8,
            'margin_bottom' => 20,
            'margin_footer' => 8,
        ], headerHtml: $headerHtml, footerHtml: $footerHtml);

        $pdfContent = $mpdf->Output('', 'S');
        $filename   = 'pago-trabajador-' . str_pad($payment->id, 8, '0', STR_PAD_LEFT) . '.pdf';

        return response($pdfContent, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"{$filename}\"",
        ]);
    }
}
