<?php

namespace App\Modules\Administrator\Academy\Http\Requests\Group;

use App\Common\Http\Requests\ApiFormRequest;

class AssignTeacherRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'group_id' => 'required|exists:academy_groups,id',
            'teacher_id' => 'nullable|exists:profile_teachers,core_person_id',
        ];
    }

    public function messages(): array
    {
        return [
            'group_id.required' => 'El grupo es requerido',
            'group_id.exists' => 'El grupo seleccionado no existe',
            'teacher_id.exists' => 'El docente seleccionado no existe',
        ];
    }
}
