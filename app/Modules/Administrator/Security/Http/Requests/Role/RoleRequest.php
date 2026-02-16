<?php

namespace App\Modules\Administrator\Security\Http\Requests\Role;

use App\Common\Http\Requests\ApiFormRequest;

class RoleRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['nullable', 'integer'],
            'display_name' => ['required', 'string', 'max:100'],
            // 'redirect_to' => ['required', 'string', 'max:255'],
            'level' => ['required', 'integer'],
            'is_active' => ['required', 'boolean'],
            // 'permissions' => ['required', 'array', 'min:1'],
            // 'permissions.*' => ['required', 'integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'display_name.required' => 'Nombre de visualización es requerido',
            'display_name.max' => 'Nombre de visualización debe tener máximo 100 caracteres',
            // 'redirect_to.required' => 'Ruta de redirección es requerida',
            // 'redirect_to.max' => 'Ruta de redirección debe tener máximo 255 caracteres',
            'level.required' => 'Nivel es requerido',
            'is_active.required' => 'Estado es requerido',
            // 'permissions.required' => 'Permisos es requerido',
            // 'permissions.array' => 'Permisos debe ser un array',
            // 'permissions.min' => 'Permisos debe tener al menos un elemento',
            // 'permissions.*.required' => 'Permiso es requerido',
            // 'permissions.*.integer' => 'Permiso debe ser un entero',
        ];
    }

    public function attributes(): array
    {
        return [
            'display_name' => 'Nombre',
            // 'redirect_to' => 'Ruta de redirección',
            'level' => 'Nivel',
            'is_active' => 'Estado',
            // 'permissions' => 'Permisos',
            // 'permissions.*' => 'Permiso',
        ];
    }
}
