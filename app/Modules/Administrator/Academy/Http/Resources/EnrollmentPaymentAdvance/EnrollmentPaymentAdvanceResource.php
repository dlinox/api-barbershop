<?php

namespace App\Modules\Administrator\Academy\Http\Resources\EnrollmentPaymentAdvance;

use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentPaymentAdvanceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'studentId' => $this->student_id,
            'amount' => (float) $this->amount,
            'observation' => $this->observation,
            'paymentDate' => $this->payment_date?->format('Y-m-d'),
            'usedAt' => $this->used_at?->format('Y-m-d'),
        ];
    }
}
