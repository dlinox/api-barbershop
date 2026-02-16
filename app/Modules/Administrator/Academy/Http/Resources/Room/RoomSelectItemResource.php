<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Room;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomSelectItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'value' => $this->id,
            'title' => 'Sede: ' . $this->branch->name . ' - ' . 'Aula: ' . $this->number,
        ];
    }
}
