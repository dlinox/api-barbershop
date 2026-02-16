<?php

namespace App\Modules\Administrator\Security\Http\Resources\Role;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleDataTableResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'displayName' => $this->display_name,
            'level' => $this->level,
            'isActive' => $this->is_active,
        ];
    }
}
