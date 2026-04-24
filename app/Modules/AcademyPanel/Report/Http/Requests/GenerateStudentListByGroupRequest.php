<?php

namespace App\Modules\AcademyPanel\Report\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class GenerateStudentListByGroupRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'group_id' => ['required', 'integer', 'exists:academy_groups,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'group_id' => 'grupo',
        ];
    }
}
