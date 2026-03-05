<?php

namespace App\Modules\Administrator\Treasury\Http\Requests\CashSession;

use App\Common\Http\Requests\ApiFormRequest;

class OpenSessionRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'cash_register_id' => 'required|integer|exists:treasury_cash_registers,id',
            'opening_amount'   => 'required|numeric|min:0',
            'notes'            => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'cash_register_id.required' => 'La caja es requerida.',
            'cash_register_id.exists'   => 'La caja seleccionada no existe.',
            'opening_amount.required'   => 'El monto inicial es requerido.',
            'opening_amount.numeric'    => 'El monto inicial debe ser un número.',
            'opening_amount.min'        => 'El monto inicial no puede ser negativo.',
        ];
    }

    public function attributes(): array
    {
        return [
            'cash_register_id' => 'Caja',
            'opening_amount'   => 'Monto inicial',
            'notes'            => 'Observaciones',
        ];
    }
}
