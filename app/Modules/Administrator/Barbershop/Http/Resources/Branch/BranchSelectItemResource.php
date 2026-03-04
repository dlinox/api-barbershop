<?php

namespace App\Modules\Administrator\Barbershop\Http\Resources\Branch;

use Illuminate\Http\Resources\Json\JsonResource;

class BranchSelectItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'value' => $this->id,
            'title' => $this->name . ($this->address ? ' (' . $this->address . ')' : ''),
        ];
    }
}
