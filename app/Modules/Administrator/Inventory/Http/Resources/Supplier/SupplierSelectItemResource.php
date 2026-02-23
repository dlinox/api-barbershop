<?php

namespace App\Modules\Administrator\Inventory\Http\Resources\Supplier;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierSelectItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'value' => $this->id,
            'title' => $this->name,
        ];
    }
}
