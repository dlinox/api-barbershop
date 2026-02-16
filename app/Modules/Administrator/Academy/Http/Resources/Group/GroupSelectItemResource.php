<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Group;

use App\Models\Academy\Enrollment;
use App\Models\Academy\EnrollmentPayment;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class GroupSelectItemResource extends JsonResource
{
    public function toArray($request)
    {
        $daysNumber = explode(',', $this->days_of_week);
        $daysOfWeek = collect($daysNumber)->map(function ($day) {
            return Carbon::now()->startOfWeek(Carbon::SUNDAY)->addDays((int)$day)->locale('es')->shortDayName;
        });

        $paymentPlans = $this->paymentPlans;

        $payments = [];

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
                        'startDate' => Carbon::parse($paymentPlan->start_date)->format('d-m-Y'),
                        'endDate' => Carbon::parse($paymentPlan->end_date)->format('d-m-Y'),
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
                        'startDate' => Carbon::parse($paymentPlan->start_date)->format('d-m-Y'),
                        'endDate' => Carbon::parse($paymentPlan->end_date)->format('d-m-Y'),
                        'subtotal' => (float)$paymentPlan->amount,
                        'discount' => 0,
                        'total' => (float)$paymentPlan->amount,
                    ];
                });
            }

            $payments = $payments->sortBy('type')->sortBy('startDate')->values();
        }


        return [
            'value' => $this->id,
            'title' => $this->name,
            'meta' => [
                'name' => $this->name,
                'startDate' => Carbon::parse($this->start_date)->format('d-m-Y'),
                'endDate' => Carbon::parse($this->end_date)->format('d-m-Y'),
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
                    'startTime' => Carbon::parse($this->schedule_start_time)->format('g:i A'), //08:00 am
                    'endTime' => Carbon::parse($this->schedule_end_time)->format('g:i A'), //05:00 pm
                ],
                // 'paymentPlans' => $this->paymentPlans->map(function ($paymentPlan) {
                //     return [
                //         'id' => $paymentPlan->id,
                //         'startDate' => Carbon::parse($paymentPlan->start_date)->format('d-m-Y'),
                //         'endDate' => Carbon::parse($paymentPlan->end_date)->format('d-m-Y'),
                //         'amount' => $paymentPlan->amount,
                //     ];
                // }),
                'payments' => $payments,
                'enrollment' => $enrollment ?? null,
            ],
        ];
    }
}
