<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Room;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'branch' => [
                'id' => $this->branch_id,
                'name' => $this->branch_name,
            ],
            'number' => $this->number,
            'capacity' => $this->capacity,
            'floor' => $this->floor,
            'isActive' => (bool) $this->is_active,
        ];
    }
}
