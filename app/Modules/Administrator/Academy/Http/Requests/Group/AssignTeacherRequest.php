<?php

namespace App\Modules\Administrator\Academy\Http\Requests\Group;

use App\Common\Http\Requests\ApiFormRequest;

class AssignTeacherRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'id' => 'nullable|exists:academy_group_teachers,id',
            'group_id' => 'required|exists:academy_groups,id',
            'teacher_id' => 'required|exists:profile_teachers,core_person_id',
            'hourly_rate' => 'required|numeric|min:0',
            'holiday_hourly_rate' => 'required|numeric|min:0',
            'status' => 'nullable|in:active,withdrawn,replaced',
            'observation' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'group_id.required' => 'El grupo es requerido',
            'group_id.exists' => 'El grupo seleccionado no existe',
            'teacher_id.required' => 'El docente es requerido',
            'teacher_id.exists' => 'El docente seleccionado no existe',
            'hourly_rate.required' => 'El sueldo por hora es requerido',
            'hourly_rate.numeric' => 'El sueldo por hora debe ser numérico',
            'holiday_hourly_rate.required' => 'El sueldo por hora festivo es requerido',
            'holiday_hourly_rate.numeric' => 'El sueldo por hora festivo debe ser numérico',
            'observation.max' => 'La observación no debe exceder 500 caracteres',
        ];
    }
}
