<?php

namespace App\Modules\BarbershopPanel\Dashboard\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'ticketsToday'       => (int)   $this->resource['tickets_today'],
            'ticketsMonth'       => (int)   $this->resource['tickets_month'],
            'revenueToday'       => (float) $this->resource['revenue_today'],
            'revenueMonth'       => (float) $this->resource['revenue_month'],
            'activeBarbersCount' => (int)   $this->resource['active_barbers_count'],
            'avgTicketMonth'     => (float) $this->resource['avg_ticket_month'],
            'cashToday'          => (float) $this->resource['cash_today'],
            'bankToday'          => (float) $this->resource['bank_today'],
            'cashMonth'          => (float) $this->resource['cash_month'],
            'bankMonth'          => (float) $this->resource['bank_month'],
        ];
    }
}