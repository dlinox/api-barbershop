<?php

namespace App\Modules\BarbershopPanel\Treasury\Repositories\Actions;

use App\Models\Treasury\EmployeePayment;
use App\Models\Profile\Barber;
use App\Models\Core\Company;
use App\Common\Helpers\PdfHelper;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class GenerateBarberPaymentPdfAction
{
    public function execute(int $paymentId): Response
    {
        $payment = EmployeePayment::with(['paymentMethod', 'paidBy'])->findOrFail($paymentId);

        $barber = Barber::with(['person', 'branch'])->find($payment->employee_id);

        $company = Company::first();

        $infrastructure = $barber?->branch;

        $calculationDetails = $payment->calculation_details ?? [];

        $data = [
            'company'             => $company,
            'branch'              => $barber?->branch ?? null,
            'payment'             => $payment,
            'barber'              => $barber,
            'infrastructure'      => $infrastructure,
            'calculation_details' => $calculationDetails,
            'generated_by'        => Auth::user()?->username ?? 'Sistema',
            'generated_at'        => now()->format('d/m/Y H:i'),
        ];

        $headerHtml = View::make('payments.common.header', $data)->render();
        $footerHtml = View::make('payments.common.footer', $data)->render();
        $html       = View::make('payments.barber-payment-voucher', $data)->render();

        $mpdf = PdfHelper::createFromHtml($html, PdfHelper::A5, config: [
            'margin_top'    => 38,
            'margin_header' => 8,
            'margin_bottom' => 20,
            'margin_footer' => 8,
        ], headerHtml: $headerHtml, footerHtml: $footerHtml);

        $pdfContent = $mpdf->Output('', 'S');
        $filename   = 'pago-barbero-' . str_pad($payment->id, 8, '0', STR_PAD_LEFT) . '.pdf';

        return response($pdfContent, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"{$filename}\"",
        ]);
    }
}
