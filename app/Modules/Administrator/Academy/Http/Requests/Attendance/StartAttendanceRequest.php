<?php

namespace App\Modules\Administrator\Academy\Http\Requests\Attendance;

use App\Common\Http\Requests\ApiFormRequest;

class StartAttendanceRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'group_id' => 'required|exists:academy_groups,id',
        ];
    }

    public function messages()
    {
        return [
            'group_id.required' => 'El grupo es requerido',
            'group_id.exists' => 'El grupo no existe',
        ];
    }

    public function attributes()
    {
        return [
            'group_id' => 'grupo',
        ];
    }
}
