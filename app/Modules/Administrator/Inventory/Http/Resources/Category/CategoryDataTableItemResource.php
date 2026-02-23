<?php

namespace App\Modules\Administrator\Inventory\Http\Resources\Category;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'parentId' => $this->parent_id,
            'parentName' => $this->parent?->name,
            'name' => $this->name,
            'type' => $this->type,
            'icon' => $this->icon,
            'isActive' => $this->is_active,
        ];
    }
}
