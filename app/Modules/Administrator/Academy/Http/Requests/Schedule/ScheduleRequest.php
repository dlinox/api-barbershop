<?php

namespace App\Modules\Administrator\Academy\Http\Requests\Schedule;

use App\Common\Http\Requests\ApiFormRequest;

class ScheduleRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id' => $id ? 'exists:academy_schedules,id' : 'nullable',
            'shift' => 'required|string|in:morning,afternoon,night',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'El horario no existe',
            'shift.required' => 'El turno es requerido',
            'shift.string' => 'El turno debe ser una cadena de texto',
            'shift.in' => 'El turno no es válido',
            'start_time.required' => 'La hora de inicio es requerida',
            'end_time.required' => 'La hora de fin es requerida',
            'end_time.after' => 'La hora de fin debe ser posterior a la hora de inicio',
            'is_active.required' => 'El estado es requerido',
            'is_active.boolean' => 'El estado debe ser un booleano',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'shift' => 'Turno',
            'start_time' => 'Hora de inicio',
            'end_time' => 'Hora de fin',
            'is_active' => 'Estado',
        ];
    }
}
