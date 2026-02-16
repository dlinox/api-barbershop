<?php

namespace App\Modules\Administrator\Academy\Http\Requests\Attendance;

use App\Common\Http\Requests\ApiFormRequest;

class UpdateAttendanceRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'group_id' => 'required|exists:academy_groups,id',
            'status' => 'required|in:check-in,check-out',
        ];
    }

    public function messages()
    {
        return [
            'group_id.required' => 'El grupo es requerido',
            'group_id.exists' => 'El grupo no existe',
            'status.required' => 'El estado es requerido',
            'status.in' => 'El estado debe ser check-in o check-out',
        ];
    }

    public function attributes()
    {
        return [
            'group_id' => 'grupo',
            'status' => 'estado',
        ];
    }
}
