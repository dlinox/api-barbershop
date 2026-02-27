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
            'details' => ['required', 'array', 'min:1'],
            'details.*.group_payment_plan_id' => ['required', 'integer', 'exists:academy_group_payment_plans,id'],
            'details.*.type' => ['required', 'string', 'in:enrollment,monthly'],
            'details.*.subtotal' => ['required', 'numeric', 'min:0'],
            'details.*.discount' => ['required', 'numeric', 'min:0'],
            'details.*.total' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'enrollment_id.required' => 'La matrícula es requerida',
            'enrollment_id.exists' => 'La matrícula no existe',
            'details.required' => 'Los detalles son requeridos',
            'details.*.group_payment_plan_id.required' => 'El plan de pago es requerido',
            'details.*.group_payment_plan_id.exists' => 'El plan de pago no existe',
            'details.*.type.required' => 'El tipo de pago es requerido',
            'details.*.type.in' => 'El tipo de pago debe ser matrícula o mensualidad',
            'details.*.subtotal.required' => 'El subtotal es requerido',
            'details.*.subtotal.numeric' => 'El subtotal debe ser un número',
            'details.*.subtotal.min' => 'El subtotal debe ser mayor o igual a 0',
            'details.*.discount.required' => 'El descuento es requerido',
            'details.*.discount.numeric' => 'El descuento debe ser un número',
            'details.*.discount.min' => 'El descuento debe ser mayor o igual a 0',
            'details.*.total.required' => 'El total es requerido',
            'details.*.total.numeric' => 'El total debe ser un número',
            'details.*.total.min' => 'El total debe ser mayor o igual a 0',
        ];
    }

    public function attributes(): array
    {
        return [
            'enrollment_id' => 'Matrícula',
            'details.*.group_payment_plan_id' => 'Plan de pago',
            'details.*.type' => 'Tipo',
            'details.*.subtotal' => 'Subtotal',
            'details.*.discount' => 'Descuento',
            'details.*.total' => 'Total',
        ];
    }
}
