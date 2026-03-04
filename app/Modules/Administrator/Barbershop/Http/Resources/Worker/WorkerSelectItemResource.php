<?php

namespace App\Modules\Administrator\Barbershop\Http\Resources\Worker;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkerSelectItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'value' => $this->id,
            'title' => $this->person_name . ' ' . $this->person_paternal_surname . ' ' . $this->person_maternal_surname . ' (' . $this->person_document_number . ')',
        ];
    }
}
