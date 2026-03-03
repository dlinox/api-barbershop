<?php

namespace App\Modules\Administrator\Academy\Http\Requests\Enrollment;

use App\Common\Http\Requests\ApiFormRequest;

class EnrollmentUpdateRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'id' => 'required|exists:academy_enrollments,id',
            'date' => 'required|date',
            'group_id' => 'required|exists:academy_groups,id',
            'status' => 'required|string|in:active,inactive,completed,cancelled',
            'materials' => 'nullable|array',
            'materials.*' => 'integer|exists:academy_materials,id',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'La matrícula es requerida',
            'id.exists' => 'La matrícula no existe',
            'date.required' => 'La fecha es requerida',
            'date.date' => 'La fecha debe ser una fecha válida',
            'group_id.required' => 'El grupo es requerido',
            'group_id.exists' => 'El grupo no existe',
            'status.required' => 'El estado es requerido',
            'status.string' => 'El estado debe ser un texto',
            'status.in' => 'El estado no es válido',
            'materials.array' => 'Los materiales deben ser un array',
            'materials.*.integer' => 'El material debe ser un entero',
            'materials.*.exists' => 'El material no existe',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'Matrícula',
            'date' => 'Fecha',
            // 'group_id' => 'Grupo',
            'status' => 'Estado',
            'materials' => 'Materiales',
        ];
    }
}
