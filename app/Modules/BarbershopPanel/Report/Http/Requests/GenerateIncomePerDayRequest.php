<?php

namespace App\Modules\BarbershopPanel\Report\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class GenerateIncomePerDayRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
        ];
    }

    public function attributes(): array
    {
        return [
            'date' => 'fecha',
        ];
    }
}
