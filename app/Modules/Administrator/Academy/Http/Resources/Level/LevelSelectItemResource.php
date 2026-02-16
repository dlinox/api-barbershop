<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Level;

use Illuminate\Http\Resources\Json\JsonResource;

class LevelSelectItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'value' => $this->id,
            'title' => $this->name,
            'meta' => [
                'order' => $this->order,
                'durationMonths' => $this->duration_months,
                'isActive' => $this->is_active,
            ],
        ];
    }
}
