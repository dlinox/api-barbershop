<?php

namespace App\Modules\Administrator\Security\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class SyncPermissionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'roleId' => ['required', 'integer', 'exists:behavior_roles,id'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['integer', 'exists:behavior_permissions,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'roleId.required' => 'El rol es requerido',
            'roleId.exists' => 'El rol seleccionado no existe',
            'permissions.required' => 'Los permisos son requeridos',
            'permissions.array' => 'Los permisos deben ser un arreglo',
            'permissions.*.integer' => 'El identificador del permiso debe ser numérico',
            'permissions.*.exists' => 'El permiso seleccionado no existe',
        ];
    }
}
