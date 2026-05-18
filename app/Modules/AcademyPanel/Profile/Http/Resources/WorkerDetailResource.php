<?php

namespace App\Modules\AcademyPanel\Profile\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkerDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        $person = $this->person;

        $paymentFrequencyLabel = match ($this->payment_frequency) {
            'weekly'    => 'Semanal',
            'biweekly'  => 'Quincenal',
            'monthly'   => 'Mensual',
            default     => $this->payment_frequency ?? '-',
        };

        return [
            'id' => (int) $this->id,

            // Datos personales
            'fullName'       => $person->full_name,
            'documentType'   => $person->documentTypeRelation?->name ?? strtoupper($person->document_type ?? ''),
            'documentNumber' => $person->document_number ?? '-',
            'dateBirth'      => $person->date_birth ? $person->date_birth->format('d/m/Y') : '-',
            'gender'         => $person->genderRelation?->name ?? '-',
            'phone'          => $person->phone ?? '-',
            'email'          => $person->email ?? '-',
            'address'        => $person->address ?? '-',

            // Datos del trabajador
            'branchName'       => $this->infrastructure?->infrastructurable?->name ?? '-',
            'position'         => $this->position ?? '-',
            'monthlySalary'    => $this->monthly_salary ? (float) $this->monthly_salary : null,
            'paymentFrequency' => $paymentFrequencyLabel,
            'isActive'         => (bool) $this->is_active,
        ];
    }
}
