<?php

namespace App\Modules\Administrator\Academy\Http\Requests\Enrollment;

use App\Common\Http\Requests\ApiFormRequest;

class EnrollmentRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'id' => 'nullable|exists:academy_enrollments,id',
            'student_id' => 'required|exists:profile_students,core_person_id',
            'materials' => 'nullable|array',
            'materials.*' => 'integer|exists:academy_materials,id',
            'date' => 'required|date',
            'group_id' => 'required|exists:academy_groups,id',
            'advance_ids' => 'nullable|array',
            'advance_ids.*' => 'integer|exists:academy_enrollment_payment_advances,id',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'No se encontro la matricula',
            'student_id.required' => 'El estudiante es requerido',
            'student_id.exists' => 'El estudiante no existe',
            'materials.array' => 'Los materiales deben ser un array',
            'materials.*.integer' => 'El material debe ser un entero',
            'materials.*.exists' => 'El material no existe',
            'date.required' => 'La fecha es requerida',
            'date.date' => 'La fecha debe ser una fecha',
            'group_id.required' => 'El grupo es requerido',
            'group_id.exists' => 'El grupo no existe',
        ];
    }

    public function attributes(): array
    {
        return [
            'student_id' => 'Estudiante',
            'date' => 'Fecha',
            'group_id' => 'Grupo',
            'materials' => 'Materiales',
        ];
    }
}
