<?php

namespace App\Modules\Administrator\Inventory\Http\Resources\Brand;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'logoUrl' => $this->logo_url,
            'isActive' => $this->is_active,
        ];
    }
}
