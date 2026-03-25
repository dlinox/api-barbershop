<?php

namespace App\Modules\Administrator\Report\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class GenerateTreasuryIncomeVsExpenseRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'infrastructure_id' => ['required', 'integer', 'exists:core_infrastructures,id'],
            'date_from'         => ['required', 'date'],
            'date_to'           => ['required', 'date', 'after_or_equal:date_from'],
        ];
    }
}
