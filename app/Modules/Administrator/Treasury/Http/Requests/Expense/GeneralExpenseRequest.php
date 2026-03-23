<?php

namespace App\Modules\Administrator\Treasury\Http\Requests\Expense;

use App\Common\Http\Requests\ApiFormRequest;

class GeneralExpenseRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $id = $this->id ?? null;
        return [
            'id'                      => $id ? 'exists:treasury_expenses,id' : 'nullable',
            'expense_type_id'         => 'required|exists:treasury_expense_types,id',
            'infrastructure_id'       => 'nullable|exists:core_infrastructures,id',
            'cash_session_id'         => 'nullable|exists:treasury_cash_sessions,id',
            'payment_method_id'       => 'nullable|exists:core_payment_methods,id',
            'amount'                  => 'required|numeric|min:0.01',
            'description'             => 'nullable|string|max:255',
            'transaction_date'        => 'required|date',
            'voucher_date'            => 'nullable|date',
            'voucher_number'          => 'nullable|string|max:255',
            'voucher_image'           => 'nullable|string|max:5600000',
            'status'                  => 'nullable|in:pending,approved,cancelled',
        ];
    }

    public function messages(): array
    {
        return [
            'expense_type_id.required'  => 'El tipo de gasto es requerido',
            'expense_type_id.exists'    => 'El tipo de gasto no existe',
            'amount.required'           => 'El monto es requerido',
            'amount.numeric'            => 'El monto debe ser numérico',
            'amount.min'                => 'El monto debe ser mayor a 0',
            'transaction_date.required' => 'La fecha de transacción es requerida',
            'transaction_date.date'     => 'La fecha de transacción debe ser una fecha válida',
        ];
    }

    public function attributes(): array
    {
        return [
            'expense_type_id'         => 'Tipo de gasto',
            'infrastructure_id'       => 'Infraestructura',
            'cash_session_id'         => 'Sesión de caja',
            'payment_method_id'       => 'Método de pago',
            'amount'                  => 'Monto',
            'description'             => 'Descripción',
            'transaction_date'        => 'Fecha de transacción',
            'voucher_date'            => 'Fecha de comprobante',
            'voucher_number'          => 'Número de comprobante',
            'voucher_image'           => 'Imagen del comprobante',
            'status'                  => 'Estado',
        ];
    }
}
