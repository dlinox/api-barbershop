<?php

namespace App\Modules\BarbershopPanel\Barbershop\Services;

use App\Models\Core\Company;
use App\Common\Helpers\PdfHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Modules\BarbershopPanel\Barbershop\Repositories\BarberRepository;
use App\Modules\Administrator\Barbershop\Repositories\Actions\CreateOrUpdateBarberAction;

class BarberService
{
    public function __construct(
        private BarberRepository $barberRepository,
        private CreateOrUpdateBarberAction $createOrUpdateBarberAction,
    ) {}

    public function dataTable($request)
    {
        return $this->barberRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->createOrUpdateBarberAction->execute($data);
    }

    public function selectAsyncItems($request)
    {
        return $this->barberRepository->selectAsyncItems($request->search, $request->value);
    }

    public function detail(int $id)
    {
        return $this->barberRepository->detail($id);
    }

    public function generatePdf(int $id)
    {
        $barber = $this->barberRepository->detail($id);
        $person = $barber->person;

        $data = [
            'company'               => Company::first(),
            'generated_by'          => Auth::user()?->username ?? 'Sistema',
            'generated_at'          => now()->format('d/m/Y H:i'),
            'barber_id'             => (int) $barber->id,
            'full_name'             => $person->full_name,
            'document_type'         => $person->documentTypeRelation?->name ?? strtoupper($person->document_type ?? ''),
            'document_number'       => $person->document_number ?? '-',
            'date_birth'            => $person->date_birth ? $person->date_birth->format('d/m/Y') : '-',
            'gender'                => $person->genderRelation?->name ?? '-',
            'phone'                 => $person->phone ?? '-',
            'email'                 => $person->email ?? '-',
            'address'               => $person->address ?? '-',
            'branch_name'           => $barber->branch?->name ?? '-',
            'commission_percentage' => $barber->commission_percentage !== null ? (float) $barber->commission_percentage : null,
            'is_active'             => (bool) $barber->is_active,
        ];

        $html       = View::make('barbers.profile', $data)->render();
        $headerHtml = View::make('barbers.header', $data)->render();
        $footerHtml = View::make('barbers.footer', $data)->render();

        $mpdf = PdfHelper::create(config: [
            'margin_top'    => 35,
            'margin_header' => 8,
            'margin_bottom' => 18,
            'margin_footer' => 8,
        ]);
        $mpdf->SetHTMLHeader($headerHtml);
        $mpdf->SetHTMLFooter($footerHtml);
        $mpdf->WriteHTML($html);

        $pdfContent = $mpdf->Output('', 'S');

        return response($pdfContent, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"ficha-barbero-{$id}.pdf\"",
        ]);
    }
}

