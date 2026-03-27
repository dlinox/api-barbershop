<?php

namespace App\Modules\StudentPanel\Dashboard\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UpcomingClassResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'date'    => $this['date'],
            'day'     => $this['day'],
            'group'   => $this['group'],
            'time'    => $this['time'],
            'room'    => $this['room'],
            'teacher' => $this['teacher'],
        ];
    }
}
