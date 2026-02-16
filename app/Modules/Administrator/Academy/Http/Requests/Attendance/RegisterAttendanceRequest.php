<?php

namespace App\Modules\Administrator\Academy\Http\Requests\Attendance;

use App\Common\Http\Requests\ApiFormRequest;

class RegisterAttendanceRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'identifier' => 'required',
            'type' => 'required|in:check-in,check-out',
        ];
    }

    public function messages()
    {
        return [
            'identifier.required' => 'El identificador es requerido',
            'type.required' => 'El tipo es requerido',
            'type.in' => 'El tipo debe ser check-in o check-out',
        ];
    }

    public function attributes()
    {
        return [
            'identifier' => 'identificador',
            'type' => 'tipo',
        ];
    }
}
