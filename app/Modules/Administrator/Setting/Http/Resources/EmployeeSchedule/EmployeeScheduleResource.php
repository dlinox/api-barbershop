<?php

namespace App\Modules\Administrator\Setting\Http\Resources\EmployeeSchedule;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'startTime' => $this->start_time,
            'endTime' => $this->end_time,
            'isActive' => $this->is_active,
        ];
    }
}
