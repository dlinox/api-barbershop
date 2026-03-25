<?php

namespace App\Modules\Administrator\Report\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class GenerateBarbershopIncomePerDayRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'integer', 'exists:barbershop_branches,id'],
            'date'      => ['required', 'date'],
        ];
    }
}
