<?php

namespace App\Modules\Administrator\Treasury\Http\Requests\EmployeeAdvance;

use App\Common\Http\Requests\ApiFormRequest;

class EmployeeAdvanceRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id' => $id ? 'exists:treasury_employee_advances,id' : 'nullable',
            'employee_type' => 'required|string',
            'employee_id' => 'required|integer',
            'infrastructure_id' => 'nullable|exists:core_infrastructure,id',
            'cash_session_id' => 'nullable|exists:treasury_cash_sessions,id',
            'payment_method_id' => 'required|exists:core_payment_methods,id',
            'authorized_by' => 'nullable|exists:auth_users,id',
            'amount' => 'required|numeric|min:0.01',
            'advance_date' => 'nullable|date',
            'payment_reference' => 'nullable|string|max:255',
            'reason' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'El adelanto no existe',
            'employee_type.required' => 'El tipo de empleado es requerido',
            'employee_type.string' => 'El tipo de empleado debe ser una cadena de texto',
            'employee_id.required' => 'El empleado es requerido',
            'employee_id.integer' => 'El empleado debe ser un número entero',
            'infrastructure_id.required' => 'La infraestructura es requerida',
            'infrastructure_id.exists' => 'La infraestructura no existe',
            'cash_session_id.exists' => 'La sesión de caja no existe',
            'payment_method_id.required' => 'El método de pago es requerido',
            'payment_method_id.exists' => 'El método de pago no existe',
            'authorized_by.exists' => 'El usuario que autoriza no existe',
            'amount.required' => 'El monto es requerido',
            'amount.numeric' => 'El monto debe ser numérico',
            'amount.min' => 'El monto debe ser mayor a 0',
            'advance_date.date' => 'La fecha del adelanto debe ser una fecha válida',
            'payment_reference.string' => 'La referencia de pago debe ser una cadena de texto',
            'payment_reference.max' => 'La referencia de pago debe tener un máximo de 255 caracteres',
            'reason.required' => 'El motivo es requerido',
            'reason.string' => 'El motivo debe ser una cadena de texto',
            'reason.max' => 'El motivo debe tener un máximo de 255 caracteres',
            'notes.string' => 'Las notas deben ser una cadena de texto',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'employee_type' => 'Tipo de empleado',
            'employee_id' => 'Empleado',
            'infrastructure_id' => 'Infraestructura',
            'cash_session_id' => 'Sesión de caja',
            'payment_method_id' => 'Método de pago',
            'authorized_by' => 'Autorizado por',
            'amount' => 'Monto',
            'advance_date' => 'Fecha del adelanto',
            'payment_reference' => 'Referencia de pago',
            'reason' => 'Motivo',
            'notes' => 'Notas',
        ];
    }
}
