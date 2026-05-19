<?php

namespace App\Modules\AcademyPanel\Academy\Services;

use App\Models\Core\Company;
use App\Common\Helpers\PdfHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Modules\AcademyPanel\Academy\Repositories\TeacherRepository;
use App\Modules\Administrator\Academy\Repositories\Actions\CreateOrUpdateTeacherAction;

class TeacherService
{
    public function __construct(
        private TeacherRepository $teacherRepository,
        private CreateOrUpdateTeacherAction $createOrUpdateTeacherAction,
    ) {}

    public function dataTable($request) { return $this->teacherRepository->dataTable($request); }
    public function save(array $data) { return $this->createOrUpdateTeacherAction->execute($data); }
    public function selectAsyncItems($request) { return $this->teacherRepository->selectAsyncItems($request->search); }

    public function detail(int $id)
    {
        return $this->teacherRepository->detail($id);
    }

    public function generatePdf(int $id)
    {
        $teacher = $this->teacherRepository->detail($id);
        $person  = $teacher->person;
        $branch  = $teacher->branch;

        $paymentTypeLabel = match ($teacher->payment_type) {
            'hourly'  => 'Por horas',
            'monthly' => 'Mensual',
            default   => $teacher->payment_type ?? '-',
        };

        $activeGroups = $teacher->groupTeachers
            ->filter(fn($gt) => $gt->group && $gt->group->is_active)
            ->values()
            ->map(fn($gt) => [
                'name'       => $gt->group->name,
                'level_name' => $gt->group->level?->name ?? '-',
                'status'     => $gt->status ?? '-',
                'start_date' => $gt->start_date ? \Carbon\Carbon::parse($gt->start_date)->format('d/m/Y') : '-',
            ])->toArray();

        $data = [
            'company'       => Company::first(),
            'branch'        => $branch ?? null,
            'generated_by'  => Auth::user()?->username ?? 'Sistema',
            'generated_at'  => now()->format('d/m/Y H:i'),
            'teacher_id'    => (int) $teacher->core_person_id,
            'full_name'     => $person->full_name,
            'document_type' => $person->documentTypeRelation?->name ?? strtoupper($person->document_type ?? ''),
            'document_number' => $person->document_number ?? '-',
            'date_birth'    => $person->date_birth ? $person->date_birth->format('d/m/Y') : '-',
            'gender'        => $person->genderRelation?->name ?? '-',
            'phone'         => $person->phone ?? '-',
            'email'         => $person->email ?? '-',
            'address'       => $person->address ?? '-',
            'branch_name'   => $branch?->name ?? '-',
            'payment_type'  => $paymentTypeLabel,
            'monthly_salary' => $teacher->monthly_salary ? (float) $teacher->monthly_salary : null,
            'is_active'     => (bool) $teacher->is_active,
            'groups'        => $activeGroups,
        ];

        $html       = View::make('teachers.profile', $data)->render();
        $headerHtml = View::make('teachers.header', $data)->render();
        $footerHtml = View::make('teachers.footer', $data)->render();

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
            'Content-Disposition' => "inline; filename=\"ficha-docente-{$id}.pdf\"",
        ]);
    }
}