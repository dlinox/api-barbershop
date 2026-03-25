<?php

namespace App\Modules\Administrator\Report\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class GenerateStudentListByGroupRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'group_id' => ['required', 'integer', 'exists:academy_groups,id'],
        ];
    }
}
