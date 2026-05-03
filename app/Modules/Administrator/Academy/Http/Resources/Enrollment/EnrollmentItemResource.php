<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Enrollment;

use App\Common\Helpers\DateHelper;
use App\Models\Academy\EnrollmentPaymentDetail;
use App\Models\Academy\Group;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class EnrollmentItemResource extends JsonResource
{
    public function toArray($request)
    {

        $group = Group::find($this->group_id);

        $payments = $group->paymentPlans->map(function ($paymentPlan) {
            $detail = EnrollmentPaymentDetail::select('academy_enrollment_payment_details.*')
                ->join('academy_enrollment_payments', 'academy_enrollment_payment_details.enrollment_payment_id', 'academy_enrollment_payments.id')
                ->where('academy_enrollment_payment_details.group_payment_plan_id', $paymentPlan->id)
                ->where('academy_enrollment_payments.enrollment_id', $this->id)
                ->first();

            return [
                'id' => $detail ? $detail->id : null,
                'planId' => $paymentPlan->id,
                'type' => $paymentPlan->type,
                'status' => $detail ? 'Pagado' : 'Pendiente',
                'startDate' => Carbon::parse($paymentPlan->start_date)->locale('es')->isoFormat('D \d\e MMM'), // 12 de Ene.
                'endDate' => Carbon::parse($paymentPlan->end_date)->locale('es')->isoFormat('D \d\e MMM'),
                'subtotal' => (float)$paymentPlan->amount,
                'discount' => $detail ? (float)$detail->discount : 0,
                'total' => $detail ? (float)$detail->total : (float)$paymentPlan->amount,
            ];
        });

        $payments = $payments->sortBy('type')->sortBy('startDate')->values();

        return [
            'id' => $this->id,
            'status' => $this->status,
            'group' => [
                'id' => $this->group_id,
                'name' => $this->group_name,
                'startDate' => $this->group_start_date,
                'endDate' => $this->group_end_date,
                'daysOfWeek' => DateHelper::getDayNamesFromCsv($this->group_days_of_week ?? ''),
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
