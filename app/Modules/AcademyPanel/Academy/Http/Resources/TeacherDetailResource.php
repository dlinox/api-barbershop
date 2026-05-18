<?php

namespace App\Modules\AcademyPanel\Academy\Http\Resources;

use App\Common\Enums\DayOfWeek;
use App\Models\Academy\Enums\Shift;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        $person = $this->person;
        $branch = $this->branch;

        $paymentTypeLabel = match ($this->payment_type) {
            'hourly'   => 'Por horas',
            'monthly'  => 'Mensual',
            default    => $this->payment_type ?? '-',
        };

        $activeGroups = $this->groupTeachers
            ->filter(fn($gt) => $gt->group && $gt->group->is_active)
            ->values();

        return [
            'id' => (int) $this->core_person_id,

            // Datos personales
            'fullName'       => $person->full_name,
            'documentType'   => $person->documentTypeRelation?->name ?? strtoupper($person->document_type ?? ''),
            'documentNumber' => $person->document_number ?? '-',
            'dateBirth'      => $person->date_birth ? $person->date_birth->format('d/m/Y') : '-',
            'gender'         => $person->genderRelation?->name ?? '-',
            'phone'          => $person->phone ?? '-',
            'email'          => $person->email ?? '-',
            'address'        => $person->address ?? '-',

            // Datos del docente
            'branchName'    => $branch?->name ?? '-',
            'paymentType'   => $paymentTypeLabel,
            'monthlySalary' => $this->monthly_salary ? (float) $this->monthly_salary : null,
            'isActive'      => (bool) $this->is_active,

            // Grupos activos asignados
            'groups' => $activeGroups->map(fn($gt) => [
                'name'      => $gt->group->name,
                'levelName' => $gt->group->level?->name ?? '-',
                'status'    => $gt->status ?? '-',
                'startDate' => $gt->start_date ? \Carbon\Carbon::parse($gt->start_date)->format('d/m/Y') : '-',
            ])->toArray(),
        ];
    }
}
