<?php

namespace App\Modules\AcademyPanel\Academy\Http\Requests\Enrollment;

use App\Common\Http\Requests\ApiFormRequest;

class ChangeGroupRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'enrollment_id'   => 'required|integer|exists:academy_enrollments,id',
            'target_group_id' => 'required|integer|exists:academy_groups,id',
            'reason'          => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'enrollment_id.required'   => 'La matrícula es requerida.',
            'enrollment_id.exists'     => 'La matrícula no existe.',
            'target_group_id.required' => 'El grupo destino es requerido.',
            'target_group_id.exists'   => 'El grupo destino no existe.',
        ];
    }

    public function attributes(): array
    {
        return [
            'enrollment_id'   => 'Matrícula',
            'target_group_id' => 'Grupo destino',
            'reason'          => 'Motivo',
        ];
    }
}
