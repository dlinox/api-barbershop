<?php

namespace App\Modules\Shared\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InfrastructureItemResource extends JsonResource
{
    private const TYPE_LABELS = [
        'academy_branches' => 'Académica',
        'barbershop_branches' => 'Barbería',
    ];

    public function toArray($request)
    {
        $type = self::TYPE_LABELS[$this->infrastructurable_type] ?? class_basename($this->infrastructurable_type);
        $name = $this->infrastructurable->name ?? 'Sin nombre';

        return [
            'id' => $this->id,
            'type' => $type,
            'name' =>  "({$type}) {$name}",
            'isActive' => $this->infrastructurable->is_active ?? false,
        ];
    }
}
