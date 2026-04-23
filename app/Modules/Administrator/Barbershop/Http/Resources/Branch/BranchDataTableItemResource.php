<?php

namespace App\Modules\Administrator\Barbershop\Http\Resources\Branch;

use Illuminate\Http\Resources\Json\JsonResource;

class BranchDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'ubication' => $this->ubication,
            'phone' => $this->phone,
            'locationLat' => $this->location_lat,
            'locationLng' => $this->location_lng,
            'logoUrl' => $this->logo,
            'isActive' => $this->is_active,
        ];
    }
}
