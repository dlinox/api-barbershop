<?php

namespace App\Modules\Administrator\Setting\Http\Resources\CalendarHoliday;

use Illuminate\Http\Resources\Json\JsonResource;

class CalendarHolidayResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'date' => $this->date->format('Y-m-d'),
            'name' => $this->name,
            'description' => $this->description,
            'isActive' => $this->is_active,
        ];
    }
}
