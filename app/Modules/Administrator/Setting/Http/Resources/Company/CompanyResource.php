<?php

namespace App\Modules\Administrator\Setting\Http\Resources\Company;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'tradeName' => $this->trade_name,
            'ruc' => $this->ruc,
            'address' => $this->address,
            'phone' => $this->phone,
            'logo' => $this->logo,
            'isActive' => $this->is_active,
        ];
    }
}
