<?php

namespace App\Modules\BarberPanel\Dashboard\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardSummaryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'ticketsToday'   => $this['tickets_today'],
            'ticketsMonth'   => $this['tickets_month'],
            'revenueMonth'   => $this['revenue_month'],
            'attendanceRate' => $this['attendance_rate'],
        ];
    }
}
