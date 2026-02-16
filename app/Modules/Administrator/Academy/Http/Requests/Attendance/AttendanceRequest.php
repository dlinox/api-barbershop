<?php

namespace App\Modules\Administrator\Academy\Http\Requests\Attendance;

use App\Common\Http\Requests\ApiFormRequest;

class AttendanceRequest extends ApiFormRequest
{

    public function rules(): array
    {
        return [
            'id' => 'required|exists:academy_attendances,id',
            'enrollment_id' => 'required|exists:academy_enrollments,id',
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'status' => 'required|string|in:present,absent,late,absent_justified,late_justified',
            'observation' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El identificador es requerido',
            'enrollment_id.required' => 'El identificador es requerido',
            'enrollment_id.exists' => 'No se encontro la matricula',
            'status.required' => 'El tipo es requerido',
            'status.in' => 'El tipo debe ser check-in o check-out',
            'observation.required' => 'La observacion es requerida',
        ];
    }

    public function attributes()
    {
        return [
            'id' => 'identificador',
            'enrollment_id' => 'matricula',
            'status' => 'estado',
            'observation' => 'observacion',
        ];
    }
}
