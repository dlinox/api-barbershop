<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Group;

use App\Models\Academy\Enrollment;
use App\Models\Academy\EnrollmentPayment;
use App\Models\Academy\EnrollmentMaterial;
use App\Models\Academy\Material;
use App\Common\Helpers\DateHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupSelectItemResource extends JsonResource
{
    public function toArray($request)
    {
        $daysOfWeek = DateHelper::getDayNamesFromCsv($this->days_of_week);

        $paymentPlans = $this->paymentPlans;

        $payments = [];
        $materials = [];

        if ($request->studentId) {

            $enrollment = Enrollment::where('group_id', $this->id)->where('profile_student_id', (int)$request->studentId)->first();

            if ($enrollment) {
                $payments = $paymentPlans->map(function ($paymentPlan) use ($enrollment) {
                    $payment = EnrollmentPayment::where('group_payment_plan_id', $paymentPlan->id)->where('enrollment_id', $enrollment->id)->first();

                    return [
                        'id' => $payment ? $payment->id : null,
                        'planId' => $paymentPlan->id,
                        'type' => $paymentPlan->type,
                        'status' => $payment ? 'Pagado' : 'Pendiente',
                        'startDate' => DateHelper::formatDate($paymentPlan->start_date),
                        'endDate' => DateHelper::formatDate($paymentPlan->end_date),
                        'subtotal' => (float)$paymentPlan->amount,
                        'discount' => $payment ? (float)$payment->discount : 0,
                        'total' => $payment ? (float)$payment->total : (float)$paymentPlan->amount,
                    ];
                });
            } else {

                $payments = $paymentPlans->map(function ($paymentPlan) {
                    return [
                        'id' => null,
                        'planId' => $paymentPlan->id,
                        'type' => $paymentPlan->type,
                        'status' => 'Pendiente',
                        'startDate' => DateHelper::formatDate($paymentPlan->start_date),
                        'endDate' => DateHelper::formatDate($paymentPlan->end_date),
                        'subtotal' => (float)$paymentPlan->amount,
                        'discount' => 0,
                        'total' => (float)$paymentPlan->amount,
                    ];
                });
            }

            $payments = $payments->sortBy('type')->sortBy('startDate')->values();
            $materials = Material::select('id', 'name')->where('is_active', true)->get();
        }


        return [
            'value' => $this->id,
            'title' => $this->name,
            'meta' => [
                'name' => $this->name,
                'startDate' => DateHelper::formatDate($this->start_date),
                'endDate' => DateHelper::formatDate($this->end_date),
                'daysOfWeek' =>  $daysOfWeek,
                'enrollmentPrice' => $this->enrollment_price,
                'monthlyPrice' => $this->monthly_price,
                'branch' => [
                    'name' => $this->branch_name,
                ],
                'room' => [
                    'number' => $this->room_number,
                ],
                'level' => [
                    'name' => $this->level_name,
                ],
                'schedule' => [
                    'shift' => $this->schedule_shift,
                    'startTime' => DateHelper::formatTime($this->schedule_start_time),
                    'endTime' => DateHelper::formatTime($this->schedule_end_time),
                ],
                'payments' => $payments,
                'materials' => $materials
            ],
        ];
    }
}
