<?php

namespace App\Modules\Administrator\Security\Http\Requests\Admin;

use App\Common\Http\Requests\ApiFormRequest;

class AssignInfrastructureRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'admin_id' => ['required', 'integer', 'exists:profile_admins,core_person_id'],
            'infrastructure_ids' => ['required', 'array'],
            'infrastructure_ids.*' => ['integer', 'exists:core_infrastructures,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'admin_id.required' => 'El ID del administrador es requerido',
            'admin_id.exists' => 'El administrador seleccionado no existe',
            'infrastructure_ids.required' => 'Las infraestructuras son requeridas',
            'infrastructure_ids.array' => 'Las infraestructuras deben ser un arreglo',
            'infrastructure_ids.*.exists' => 'Una de las infraestructuras seleccionadas no existe',
        ];
    }
}
