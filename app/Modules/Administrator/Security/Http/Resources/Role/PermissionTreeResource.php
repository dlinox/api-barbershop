<?php

namespace App\Modules\Administrator\Security\Http\Resources\Role;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionTreeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $permission = [
            'id' => $this->id,
            'displayName' => $this->display_name,
        ];

        if ($this->children->count() > 0) {
            $permission['children'] = PermissionTreeResource::collection($this->children);
        }

        return $permission;
    }
}
