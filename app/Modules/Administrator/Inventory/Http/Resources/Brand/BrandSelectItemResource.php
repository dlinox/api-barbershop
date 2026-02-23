<?php

namespace App\Modules\Administrator\Inventory\Http\Resources\Brand;

use Illuminate\Http\Resources\Json\JsonResource;

class BrandSelectItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'value' => $this->id,
            'title' => $this->name,
        ];
    }
}
