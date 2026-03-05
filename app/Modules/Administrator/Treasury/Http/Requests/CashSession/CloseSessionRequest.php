<?php

namespace App\Modules\Administrator\Treasury\Http\Requests\CashSession;

use App\Common\Http\Requests\ApiFormRequest;

class CloseSessionRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'cash_session_id'      => 'required|integer|exists:treasury_cash_sessions,id',
            'actual_closing_amount' => 'required|numeric|min:0',
            'notes'                => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'cash_session_id.required'          => 'La sesión es requerida.',
            'cash_session_id.exists'            => 'La sesión no existe.',
            'actual_closing_amount.required'    => 'El monto contado es requerido.',
            'actual_closing_amount.numeric'     => 'El monto contado debe ser un número.',
            'actual_closing_amount.min'         => 'El monto contado no puede ser negativo.',
        ];
    }

    public function attributes(): array
    {
        return [
            'cash_session_id'       => 'Sesión',
            'actual_closing_amount' => 'Monto contado',
            'notes'                 => 'Observaciones',
        ];
    }
}
