<?php

namespace App\Modules\Administrator\Inventory\Http\Resources\Supplier;

use Illuminate\Http\Resources\Json\JsonResource;

class SupplierDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'contactName' => $this->contact_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'isActive' => $this->is_active,
        ];
    }
}
