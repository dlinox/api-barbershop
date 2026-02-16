<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Branch;

use Illuminate\Http\Resources\Json\JsonResource;

class BranchDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'isActive' => $this->is_active,
        ];
    }
}
