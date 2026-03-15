<?php

namespace App\Modules\Administrator\Setting\Http\Resources\DocumentType;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentTypeDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'isActive' => $this->is_active,
        ];
    }
}
