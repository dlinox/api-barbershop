<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Enrollment;

use App\Common\Helpers\DateHelper;
use App\Models\Academy\EnrollmentPayment;
use App\Models\Academy\EnrollmentPaymentDetail;
use App\Models\Academy\Group;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class EnrollmentDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {

        $id = $this->id;
        $group = Group::find($this->group_id);

        $payments = $group->paymentPlans->map(function ($paymentPlan) use ($id) {

            $payment = EnrollmentPaymentDetail::select('academy_enrollment_payment_details.*')
                ->join('academy_enrollment_payments', 'academy_enrollment_payment_details.enrollment_payment_id', 'academy_enrollment_payments.id')
                ->where('academy_enrollment_payment_details.group_payment_plan_id', $paymentPlan->id)
                ->where('academy_enrollment_payments.enrollment_id', $id)
                ->first();

            return [
                'id' => $payment ? $payment->id : null,
                'planId' => $paymentPlan->id,
                'type' => $paymentPlan->type,
                'status' => $payment ? 'Pagado' : 'Pendiente',
                'startDate' => Carbon::parse($paymentPlan->start_date)->locale('es')->isoFormat('D \d\e MMM'), // 12 de Ene.
                'endDate' => Carbon::parse($paymentPlan->end_date)->locale('es')->isoFormat('D \d\e MMM'),
                'subtotal' => (float)$paymentPlan->amount,
                'discount' => $payment ? (float)$payment->discount : 0,
                'total' => $payment ? (float)$payment->total : (float)$paymentPlan->amount,
            ];
        });

        $payments = $payments->sortBy('type')->values();

        //ids de materiales
        $materials = $this->materials->pluck('id')->toArray();

        return [
            'id' => $this->id,
            'status' => $this->status,
            'date' =>  $this->date,
            'student' => [
                'id' => $this->student_id,
                'person' => [
                    'name' => $this->student_person_name,
                    'paternalSurname' => $this->student_person_paternal_surname,
                    'maternalSurname' => $this->student_person_maternal_surname,
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
            'materials' => $materials,
        ];
    }
}
