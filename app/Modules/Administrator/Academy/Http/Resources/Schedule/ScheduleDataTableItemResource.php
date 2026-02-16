<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Schedule;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'shift' => $this->shift,
            'startTime' => $this->start_time,
            'endTime' => $this->end_time,
            'isActive' => $this->is_active,
        ];
    }
}
