<?php

namespace App\Modules\Administrator\Academy\Http\Requests\EnrollmentPayment;

use App\Common\Http\Requests\ApiFormRequest;

class EnrollmentPaymentRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['nullable', 'integer', 'exists:academy_enrollment_payments,id'],
            'enrollment_id' => ['required', 'integer', 'exists:academy_enrollments,id'],
            'group_payment_plan_id' => ['required', 'integer', 'exists:academy_group_payment_plans,id'],
            'type' => ['required', 'string', 'in:enrollment,monthly'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'discount' => ['required', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'enrollment_id.required' => 'La matrícula es requerida',
            'enrollment_id.exists' => 'La matrícula no existe',
            'group_payment_plan_id.required' => 'El plan de pago es requerido',
            'group_payment_plan_id.exists' => 'El plan de pago no existe',
            'type.required' => 'El tipo de pago es requerido',
            'type.in' => 'El tipo de pago debe ser matrícula o mensualidad',
            'subtotal.required' => 'El subtotal es requerido',
            'subtotal.numeric' => 'El subtotal debe ser un número',
            'subtotal.min' => 'El subtotal debe ser mayor o igual a 0',
            'discount.required' => 'El descuento es requerido',
            'discount.numeric' => 'El descuento debe ser un número',
            'discount.min' => 'El descuento debe ser mayor o igual a 0',
            'total.required' => 'El total es requerido',
            'total.numeric' => 'El total debe ser un número',
            'total.min' => 'El total debe ser mayor o igual a 0',
        ];
    }

    public function attributes(): array
    {
        return [
            'enrollment_id' => 'Matrícula',
            'group_payment_plan_id' => 'Plan de pago',
            'type' => 'Tipo',
            'subtotal' => 'Subtotal',
            'discount' => 'Descuento',
            'total' => 'Total',
        ];
    }
}
