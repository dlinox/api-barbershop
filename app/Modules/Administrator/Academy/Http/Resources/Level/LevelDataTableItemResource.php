<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Level;

use Illuminate\Http\Resources\Json\JsonResource;

class LevelDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'order' => $this->order,
            'durationMonths' => $this->duration_months,
            'isActive' => $this->is_active,
        ];
    }
}
