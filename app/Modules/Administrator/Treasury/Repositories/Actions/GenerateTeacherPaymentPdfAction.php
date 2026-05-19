<?php

namespace App\Modules\Administrator\Treasury\Repositories\Actions;

use App\Models\Treasury\EmployeePayment;
use App\Models\Profile\Teacher;
use App\Models\Core\Company;
use App\Common\Helpers\PdfHelper;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class GenerateTeacherPaymentPdfAction
{
    public function execute(int $paymentId): Response
    {
        $payment = EmployeePayment::with(['paymentMethod', 'paidBy'])->findOrFail($paymentId);

        $teacher = Teacher::with(['person', 'branch'])->find($payment->employee_id);

        $infrastructure = $teacher?->branch;

        $company = Company::first();

        $calculationDetails = $payment->calculation_details ?? [];

        $data = [
            'company'             => $company,
            'branch'              => $teacher?->branch ?? null,
            'payment'             => $payment,
            'teacher'             => $teacher,
            'infrastructure'      => $infrastructure,
            'calculation_details' => $calculationDetails,
            'generated_by'        => Auth::user()?->username ?? 'Sistema',
            'generated_at'        => now()->format('d/m/Y H:i'),
        ];

        $headerHtml = View::make('payments.common.header', $data)->render();
        $footerHtml = View::make('payments.common.footer', $data)->render();
        $html       = View::make('payments.teacher-payment-voucher', $data)->render();

        $mpdf = PdfHelper::createFromHtml($html, PdfHelper::A5, config: [
            'margin_top'    => 38,
            'margin_header' => 8,
            'margin_bottom' => 20,
            'margin_footer' => 8,
        ], headerHtml: $headerHtml, footerHtml: $footerHtml);

        $pdfContent = $mpdf->Output('', 'S');
        $filename   = 'pago-docente-' . str_pad($payment->id, 8, '0', STR_PAD_LEFT) . '.pdf';

        return response($pdfContent, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"{$filename}\"",
        ]);
    }
}
