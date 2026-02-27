<?php

namespace App\Modules\Administrator\Academy\Http\Requests\Enrollment;

use App\Common\Http\Requests\ApiFormRequest;
use App\Modules\Administrator\Treasury\Http\Requests\Income\IncomeRequest;

class EnrollmentRegisterPaymentRequest extends ApiFormRequest
{
    public function rules()
    {
        $incomeRequest = new IncomeRequest();
        $incomeRules = collect($incomeRequest->rules())
            ->mapWithKeys(fn($rule, $key) => ["income.{$key}" => $rule])
            ->all();

        $rules = [
            'enrollment_id' => 'required|exists:academy_enrollments,id',
            'payments' => 'required|array',
            'payments.*.id' => 'nullable|exists:academy_enrollment_payments,id',
            'payments.*.plan_id' => 'required|exists:academy_group_payment_plans,id',
            'payments.*.type' => 'required',
            'payments.*.subtotal' => 'required|numeric',
            'payments.*.discount' => 'required|numeric',
            'payments.*.total' => 'required|numeric',
        ];

        return array_merge($rules, $incomeRules);
    }

    public function messages(): array
    {
        $incomeRequest = new IncomeRequest();

        $incomeMessages = collect($incomeRequest->messages())
            ->mapWithKeys(fn($message, $key) => ["income.{$key}" => $message])
            ->all();

        return array_merge([
            'enrollment_id.required' => 'La matricula es requerida',
            'enrollment_id.exists' => 'La matricula no existe',
            'payments.required' => 'Los pagos son requeridos',
            'payments.array' => 'Los pagos deben ser un array',
            'payments.*.id.exists' => 'El pago no existe',
            'payments.*.plan_id.required' => 'El plan es requerido',
            'payments.*.plan_id.exists' => 'El plan no existe',
            'payments.*.type.required' => 'El tipo es requerido',
            'payments.*.subtotal.required' => 'El subtotal es requerido',
            'payments.*.subtotal.numeric' => 'El subtotal debe ser un número',
            'payments.*.discount.required' => 'El descuento es requerido',
            'payments.*.discount.numeric' => 'El descuento debe ser un número',
            'payments.*.total.required' => 'El total es requerido',
            'payments.*.total.numeric' => 'El total debe ser un número',
        ], $incomeMessages);
    }

    public function attributes(): array
    {
        $incomeRequest = new IncomeRequest();
        $incomeAttributes = collect($incomeRequest->attributes())
            ->mapWithKeys(fn($attribute, $key) => ["income.{$key}" => $attribute])
            ->all();

        return array_merge([
            'enrollment_id' => 'Matricula',
            'payments' => 'Pagos',
            'payments.*.id' => 'ID',
            'payments.*.plan_id' => 'Plan',
            'payments.*.type' => 'Tipo',
            'payments.*.subtotal' => 'Subtotal',
            'payments.*.discount' => 'Descuento',
            'payments.*.total' => 'Total',
        ], $incomeAttributes);
    }
}
