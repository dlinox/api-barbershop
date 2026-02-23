<?php

namespace App\Modules\Administrator\Academy\Http\Resources\EnrollmentPayment;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class EnrollmentPaymentDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'enrollmentId' => $this->enrollment_id,
            'type' => $this->type,
            'subtotal' => (float) $this->subtotal,
            'discount' => (float) $this->discount,
            'total' => (float) $this->total,
            'createdAt' => Carbon::parse($this->created_at)->format('d \d\e M Y'),
            'plan' => [
                'id' => $this->group_payment_plan_id,
                'startDate' => Carbon::parse($this->plan_start_date)->locale('es')->isoFormat('D \d\e MMM'),
                'endDate' => Carbon::parse($this->plan_end_date)->locale('es')->isoFormat('D \d\e MMM'),
            ],
            'student' => [
                'id' => $this->student_id,
                'person' => [
                    'name' => $this->student_person_name,
                    'paternalSurname' => $this->student_person_paternal_surname,
                    'maternalSurname' => $this->student_person_maternal_surname,
                    'documentNumber' => $this->student_person_document_number,
                ],
            ],
            'group' => [
                'id' => $this->group_id,
                'name' => $this->group_name,
                'startDate' => $this->group_start_date,
                'endDate' => $this->group_end_date,
                'levelName' => $this->group_level_name,
            ],
        ];
    }
}
