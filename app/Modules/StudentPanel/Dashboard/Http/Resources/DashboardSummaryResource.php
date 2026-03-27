<?php

namespace App\Modules\StudentPanel\Dashboard\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardSummaryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'activeEnrollments'    => $this['active_enrollments'],
            'attendanceRate'       => $this['attendance_rate'],
            'pendingPayments'      => $this['pending_payments'],
            'pendingPaymentsAmount' => (float) $this['pending_payments_amount'],
            'nextClass'            => $this['next_class'],
        ];
    }
}
