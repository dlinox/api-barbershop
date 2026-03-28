<?php

namespace App\Modules\BarberPanel\CashRegister\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class OpenSessionRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'cash_register_id' => ['required', 'integer', 'exists:treasury_cash_registers,id'],
            'opening_amount'   => ['required', 'numeric', 'min:0'],
            'notes'            => ['nullable', 'string', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'cash_register_id' => 'caja registradora',
            'opening_amount'   => 'monto de apertura',
            'notes'            => 'notas',
        ];
    }
}
