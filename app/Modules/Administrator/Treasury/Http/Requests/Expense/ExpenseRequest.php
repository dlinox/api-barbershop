<?php

namespace App\Modules\Administrator\Treasury\Http\Requests\Expense;

use App\Common\Http\Requests\ApiFormRequest;

class ExpenseRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id'                => $id ? 'exists:treasury_expenses,id' : 'nullable',
            'cash_session_id'   => 'required|exists:treasury_cash_sessions,id',
            'expense_type_id'   => 'required|exists:treasury_expense_types,id',
            'payment_method_id' => 'nullable|exists:core_payment_methods,id',
            'amount'            => 'required|numeric|min:0.01',
            'description'       => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists'                  => 'El gasto no existe',
            'cash_session_id.required'   => 'La sesión de caja es requerida',
            'cash_session_id.exists'     => 'La sesión de caja no existe',
            'expense_type_id.required'   => 'El tipo de gasto es requerido',
            'expense_type_id.exists'     => 'El tipo de gasto no existe',
            'payment_method_id.exists'   => 'El método de pago no existe',
            'amount.required'            => 'El monto es requerido',
            'amount.numeric'             => 'El monto debe ser numérico',
            'amount.min'                 => 'El monto debe ser mayor a 0',
            'description.required'       => 'La descripción es requerida',
            'description.string'         => 'La descripción debe ser una cadena de texto',
            'description.max'            => 'La descripción debe tener un máximo de 255 caracteres',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'                => 'ID',
            'cash_session_id'   => 'Sesión de caja',
            'expense_type_id'   => 'Tipo de gasto',
            'payment_method_id' => 'Método de pago',
            'amount'            => 'Monto',
            'description'       => 'Descripción',
        ];
    }
}
