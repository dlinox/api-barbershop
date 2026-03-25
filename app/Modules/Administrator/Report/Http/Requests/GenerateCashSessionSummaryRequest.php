<?php

namespace App\Modules\Administrator\Report\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class GenerateCashSessionSummaryRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'cash_session_id' => ['required', 'integer', 'exists:treasury_cash_sessions,id'],
        ];
    }
}
