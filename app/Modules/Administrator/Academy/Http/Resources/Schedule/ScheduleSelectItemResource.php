<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Schedule;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleSelectItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'value' => $this->id,
            'title' => ucfirst($this->shift) . ' (' . $this->start_time . ' - ' . $this->end_time . ')',
        ];
    }
}
