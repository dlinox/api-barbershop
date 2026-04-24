<?php

namespace App\Modules\BarbershopPanel\Report\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class GenerateBarberCommissionsRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'date_from' => ['required', 'date'],
            'date_to'   => ['required', 'date', 'after_or_equal:date_from'],
        ];
    }

    public function attributes(): array
    {
        return [
            'date_from' => 'fecha desde',
            'date_to'   => 'fecha hasta',
        ];
    }
}
