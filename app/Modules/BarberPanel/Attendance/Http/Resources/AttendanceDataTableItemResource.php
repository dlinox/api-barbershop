<?php

namespace App\Modules\BarberPanel\Attendance\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceDataTableItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'date'        => $this->date?->format('Y-m-d'),
            'checkIn'     => $this->check_in ? substr($this->check_in, 0, 5) : null,
            'checkOut'    => $this->check_out ? substr($this->check_out, 0, 5) : null,
            'status'      => $this->status,
            'observation' => $this->observation,
            'branch'      => $this->branch_name,
        ];
    }
}
