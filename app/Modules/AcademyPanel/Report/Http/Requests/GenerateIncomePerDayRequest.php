<?php

namespace App\Modules\AcademyPanel\Report\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class GenerateIncomePerDayRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'date'      => ['required', 'date'],
            'worker_id' => ['required', 'integer', 'exists:profile_workers,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'date'      => 'fecha',
            'worker_id' => 'trabajador',
        ];
    }
}
