<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Enrollment;

use App\Models\Academy\EnrollmentPayment;
use App\Models\Academy\Group;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {

        $group = Group::find($this->group_id);

        $payments = $group->paymentPlans->map(function ($paymentPlan) {
            $payment = EnrollmentPayment::where('group_payment_plan_id', $paymentPlan->id)->where('enrollment_id', $this->id)->first();

            return [
                'id' => $payment ? $payment->id : null,
                'planId' => $paymentPlan->id,
                'type' => $paymentPlan->type,
                'status' => $payment ? 'Pagado' : 'Pendiente',
                'startDate' => $paymentPlan->start_date->format('d-m-Y'),
                'endDate' => $paymentPlan->end_date->format('d-m-Y'),
                'subtotal' => (float)$paymentPlan->amount,
                'discount' => $payment ? (float)$payment->discount : 0,
                'total' => $payment ? (float)$payment->total : (float)$paymentPlan->amount,
            ];
        });

        $payments = $payments->sortBy('type')->sortBy('startDate')->values();

        return [
            'id' => $this->id,
            'status' => $this->status,
            'createdAt' =>  $this->created_at->format('d \d\e M Y'),
            'student' => [
                'id' => $this->student_id,
                'person' => [
                    'name' => $this->student_person_name,
                    'surname' => collect([$this->student_person_paternal_surname, $this->student_person_maternal_surname])->filter()->implode(' '),
                    'phone' => $this->student_person_phone,
                ],
            ],
            'group' => [
                'id' => $this->group_id,
                'name' => $this->group_name,
                'startDate' => $this->group_start_date,
                'endDate' => $this->group_end_date,
                'daysOfWeek' => $this->group_days_of_week,
                'isActive' => $this->group_is_active,
                'level' => [
                    'id' => $this->group_level_id,
                    'name' => $this->group_level_name,
                ],
            ],
            'payments' => $payments,
        ];
    }
}
