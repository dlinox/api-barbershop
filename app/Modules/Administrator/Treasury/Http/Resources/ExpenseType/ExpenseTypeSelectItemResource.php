<?php

namespace App\Modules\Administrator\Treasury\Http\Resources\ExpenseType;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseTypeSelectItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'value' => $this->id,
            'title' => $this->name,
        ];
    }
}
