<?php

namespace App\Modules\Administrator\Academy\Http\Requests\StudentGuardian;

use App\Common\Http\Requests\ApiFormRequest;

class StudentGuardianRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $id = $this->id ?? null;

        return [
            'id' => $id ? 'exists:academy_student_guardians,id' : 'nullable',
            'student_id' => ['required', 'integer', 'exists:profile_students,core_person_id'],
            'full_name' => ['required', 'string', 'max:150'],
            'kinship' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'string', 'max:15'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'El apoderado no existe',
            'student_id.required' => 'El estudiante es requerido',
            'student_id.exists' => 'El estudiante no existe',
            'full_name.required' => 'El nombre completo es requerido',
            'full_name.max' => 'El nombre completo debe tener máximo 150 caracteres',
            'kinship.required' => 'El parentesco es requerido',
            'kinship.max' => 'El parentesco debe tener máximo 50 caracteres',
            'phone.required' => 'El celular es requerido',
            'phone.max' => 'El celular debe tener máximo 15 caracteres',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'student_id' => 'Estudiante',
            'full_name' => 'Nombre completo',
            'kinship' => 'Parentesco',
            'phone' => 'Celular',
        ];
    }
}
