<?php

namespace App\Modules\AcademyPanel\Profile\Services;

use App\Models\Core\Company;
use App\Common\Helpers\PdfHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Modules\AcademyPanel\Profile\Repositories\WorkerRepository;
use App\Modules\AcademyPanel\Profile\Repositories\Actions\CreateOrUpdateWorkerAction;

class WorkerService
{
    public function __construct(
        private WorkerRepository $workerRepository,
        private CreateOrUpdateWorkerAction $createOrUpdateWorkerAction,
    ) {}

    public function dataTable($request)
    {
        return $this->workerRepository->dataTable($request);
    }

    public function save(array $data): void
    {
        $this->createOrUpdateWorkerAction->execute($data);
    }

    public function delete(int $id): void
    {
        $this->workerRepository->delete($id);
    }

    public function selectAsyncItems($request)
    {
        return $this->workerRepository->selectAsyncItems($request->search ?? null);
    }

    public function detail(int $id)
    {
        return $this->workerRepository->detail($id);
    }

    public function generatePdf(int $id)
    {
        $worker = $this->workerRepository->detail($id);
        $person = $worker->person;

        $paymentFrequencyLabel = match ($worker->payment_frequency) {
            'weekly'   => 'Semanal',
            'biweekly' => 'Quincenal',
            'monthly'  => 'Mensual',
            default    => $worker->payment_frequency ?? '-',
        };

        $data = [
            'company'           => Company::first(),
            'branch'            => $worker->infrastructure?->infrastructurable ?? null,
            'generated_by'      => Auth::user()?->username ?? 'Sistema',
            'generated_at'      => now()->format('d/m/Y H:i'),
            'worker_id'         => (int) $worker->id,
            'full_name'         => $person->full_name,
            'document_type'     => $person->documentTypeRelation?->name ?? strtoupper($person->document_type ?? ''),
            'document_number'   => $person->document_number ?? '-',
            'date_birth'        => $person->date_birth ? $person->date_birth->format('d/m/Y') : '-',
            'gender'            => $person->genderRelation?->name ?? '-',
            'phone'             => $person->phone ?? '-',
            'email'             => $person->email ?? '-',
            'address'           => $person->address ?? '-',
            'branch_name'       => $worker->infrastructure?->infrastructurable?->name ?? '-',
            'position'          => $worker->position ?? '-',
            'monthly_salary'    => $worker->monthly_salary ? (float) $worker->monthly_salary : null,
            'payment_frequency' => $paymentFrequencyLabel,
            'is_active'         => (bool) $worker->is_active,
        ];

        $html       = View::make('workers.profile', $data)->render();
        $headerHtml = View::make('workers.header', $data)->render();
        $footerHtml = View::make('workers.footer', $data)->render();

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
            'Content-Disposition' => "inline; filename=\"ficha-trabajador-{$id}.pdf\"",
        ]);
    }
}
