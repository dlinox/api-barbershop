<?php

namespace App\Modules\Administrator\Treasury\Http\Resources\ExpenseType;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseTypeDataTableItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'description' => $this->description,
            'isActive'    => $this->is_active,
        ];
    }
}
