<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Schedule;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleSelectItemResource extends JsonResource
{
    private static array $shiftLabels = [
        'morning'   => 'Mañana',
        'afternoon' => 'Tarde',
        'night'     => 'Noche',
    ];

    public function toArray($request)
    {
        $shiftLabel = self::$shiftLabels[$this->shift] ?? ucfirst($this->shift);
        $start = \Carbon\Carbon::createFromTimeString($this->start_time)->format('h:i A');
        $end   = \Carbon\Carbon::createFromTimeString($this->end_time)->format('h:i A');

        return [
            'value' => $this->id,
            'title' => "{$shiftLabel} ({$start} - {$end})",
        ];
    }
}
