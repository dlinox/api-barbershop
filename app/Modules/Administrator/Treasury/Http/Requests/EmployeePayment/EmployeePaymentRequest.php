<?php

namespace App\Modules\Administrator\Treasury\Http\Requests\EmployeePayment;

use App\Common\Http\Requests\ApiFormRequest;

class EmployeePaymentRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id' => $id ? 'exists:treasury_employee_payments,id' : 'nullable',
            'employee_type' => 'required|string',
            'employee_id' => 'required|integer',
            'infrastructure_id' => 'nullable|exists:core_infrastructures,id',
            'cash_session_id' => 'nullable|exists:treasury_cash_sessions,id',
            'payment_method_id' => 'required|exists:core_payment_methods,id',
            'period' => 'required|string|max:50',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'base_amount' => 'required|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'calculation_details' => 'nullable|array',
            'payment_date' => 'nullable|date',
            'payment_reference' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'El pago no existe',
            'employee_type.required' => 'El tipo de empleado es requerido',
            'employee_id.required' => 'El empleado es requerido',
            'payment_method_id.required' => 'El método de pago es requerido',
            'payment_method_id.exists' => 'El método de pago no existe',
            'period.required' => 'El periodo es requerido',
            'period_start.required' => 'La fecha de inicio es requerida',
            'period_start.date' => 'La fecha de inicio debe ser una fecha válida',
            'period_end.required' => 'La fecha de fin es requerida',
            'period_end.date' => 'La fecha de fin debe ser una fecha válida',
            'period_end.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio',
            'base_amount.required' => 'El monto base es requerido',
            'base_amount.numeric' => 'El monto base debe ser numérico',
            'base_amount.min' => 'El monto base debe ser mayor o igual a 0',
            'total_amount.required' => 'El monto total es requerido',
            'total_amount.numeric' => 'El monto total debe ser numérico',
            'total_amount.min' => 'El monto total debe ser mayor o igual a 0',
            'payment_reference.max' => 'La referencia debe tener un máximo de 255 caracteres',
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
            'period' => 'Periodo',
            'period_start' => 'Fecha de inicio',
            'period_end' => 'Fecha de fin',
            'base_amount' => 'Monto base',
            'bonus' => 'Bonificación',
            'deductions' => 'Deducciones',
            'total_amount' => 'Monto total',
            'payment_date' => 'Fecha de pago',
            'payment_reference' => 'Referencia de pago',
            'notes' => 'Notas',
        ];
    }
}
