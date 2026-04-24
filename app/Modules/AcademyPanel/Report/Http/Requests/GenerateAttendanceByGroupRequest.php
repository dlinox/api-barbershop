<?php

namespace App\Modules\AcademyPanel\Report\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class GenerateAttendanceByGroupRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'group_id' => ['required', 'integer', 'exists:academy_groups,id'],
            'month'    => ['required', 'integer', 'min:1', 'max:12'],
            'year'     => ['required', 'integer', 'min:2000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'group_id' => 'grupo',
            'month'    => 'mes',
            'year'     => 'año',
        ];
    }
}
