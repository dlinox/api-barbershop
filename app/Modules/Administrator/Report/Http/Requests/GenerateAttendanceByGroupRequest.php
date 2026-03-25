<?php

namespace App\Modules\Administrator\Report\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class GenerateAttendanceByGroupRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'group_id' => ['required', 'integer', 'exists:academy_groups,id'],
            'month'    => ['required', 'integer', 'min:1', 'max:12'],
            'year'     => ['required', 'integer', 'min:2020', 'max:2100'],
        ];
    }
}
