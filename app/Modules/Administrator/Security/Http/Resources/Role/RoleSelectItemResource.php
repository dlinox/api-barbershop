<?php

namespace App\Modules\Administrator\Security\Http\Resources\Role;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleSelectItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'value' => $this->id,
            'title' => $this->display_name,
        ];
    }
}
