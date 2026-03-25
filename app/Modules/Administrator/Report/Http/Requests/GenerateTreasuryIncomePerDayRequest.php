<?php

namespace App\Modules\Administrator\Report\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class GenerateTreasuryIncomePerDayRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'infrastructure_id' => ['required', 'integer', 'exists:core_infrastructures,id'],
            'date'              => ['required', 'date'],
        ];
    }
}
