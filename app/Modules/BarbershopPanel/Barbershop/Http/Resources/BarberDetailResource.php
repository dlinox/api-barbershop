<?php

namespace App\Modules\BarbershopPanel\Barbershop\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BarberDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        $person = $this->person;

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

            // Datos del barbero
            'branchName'            => $this->branch?->name ?? '-',
            'commissionPercentage'  => $this->commission_percentage !== null ? (float) $this->commission_percentage : null,
            'isActive'              => (bool) $this->is_active,
        ];
    }
}
