<?php

namespace App\Modules\Administrator\Setting\Http\Resources\DocumentType;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentTypeSelectItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'value' => $this->code,
            'title' => $this->name,
        ];
    }
}
