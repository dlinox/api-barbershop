<?php

namespace App\Modules\BarberPanel\Dashboard\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RecentAttendanceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'       => $this['id'],
            'date'     => $this['date'],
            'checkIn'  => $this['check_in'],
            'checkOut' => $this['check_out'],
            'status'   => $this['status'],
        ];
    }
}
