<?php

namespace App\Modules\Administrator\Report\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class GenerateIncomePerDayRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'integer', 'exists:academy_branches,id'],
            'date'      => ['required', 'date'],
            'worker_id' => ['required', 'integer', 'exists:profile_workers,id'],
        ];
    }
}
