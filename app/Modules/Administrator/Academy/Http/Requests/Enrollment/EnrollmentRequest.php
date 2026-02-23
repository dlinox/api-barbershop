<?php

namespace App\Modules\Administrator\Academy\Http\Requests\Enrollment;

use App\Common\Http\Requests\ApiFormRequest;

class EnrollmentRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id' => 'nullable|exists:academy_enrollments,id',
            'student_id' => 'required|exists:profile_students,core_person_id',
            'materials' => $id ? 'nullable|array' : 'nullable|array',
            'materials.*' => 'integer|exists:academy_materials,id',
            'date' => 'required|date',
            'group_id' => 'required|exists:academy_groups,id',
            'payments' => 'required|array',
            'payments.*.id' => 'nullable|exists:academy_enrollment_payments,id',
            'payments.*.plan_id' => 'required|exists:academy_group_payment_plans,id',
            'payments.*.type' => 'required|string|in:enrollment,monthly',
            'payments.*.start_date' => 'required|date',
            'payments.*.end_date' => 'required|date',
            'payments.*.subtotal' => 'required|numeric',
            'payments.*.discount' => 'required|numeric',
            'payments.*.total' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'No se encontro la matricula',
            'student_id.required' => 'El estudiante es requerido',
            'student_id.exists' => 'El estudiante no existe',
            'materials.required' => 'Los materiales son requeridos',
            'materials.array' => 'Los materiales deben ser un array',
            'materials.*.integer' => 'El material debe ser un entero',
            'materials.*.exists' => 'El material no existe',
            'date.required' => 'La fecha es requerida',
            'date.date' => 'La fecha debe ser una fecha',
            'group_id.required' => 'El grupo es requerido',
            'group_id.exists' => 'El grupo no existe',
            'payments.required' => 'Los pagos son requeridos',
            'payments.array' => 'Los pagos deben ser un array',
            'payments.*.id.exists' => 'El pago no existe',
            'payments.*.planId.required' => 'El plan es requerido',
            'payments.*.planId.exists' => 'El plan no existe',
            'payments.*.type.required' => 'El tipo es requerido',
            'payments.*.type.in' => 'El tipo no es válido',
            'payments.*.startDate.required' => 'La fecha de inicio es requerida',
            'payments.*.startDate.date' => 'La fecha de inicio debe ser una fecha',
            'payments.*.endDate.required' => 'La fecha de fin es requerida',
            'payments.*.endDate.date' => 'La fecha de fin debe ser una fecha',
            'payments.*.subtotal.required' => 'El subtotal es requerido',
            'payments.*.subtotal.numeric' => 'El subtotal debe ser un número',
            'payments.*.discount.required' => 'El descuento es requerido',
            'payments.*.discount.numeric' => 'El descuento debe ser un número',
            'payments.*.total.required' => 'El total es requerido',
            'payments.*.total.numeric' => 'El total debe ser un número',
        ];
    }

    public function attributes(): array
    {
        return [
            'student_id' => 'Estudiante',
            'date' => 'Fecha',
            'group_id' => 'Grupo',
            'materials' => 'Materiales',
            'payments' => 'Pagos',
            'payments.*.id' => 'ID',
            'payments.*.planId' => 'Plan',
            'payments.*.type' => 'Tipo',
            'payments.*.startDate' => 'Fecha de inicio',
            'payments.*.endDate' => 'Fecha de fin',
            'payments.*.subtotal' => 'Subtotal',
            'payments.*.discount' => 'Descuento',
            'payments.*.total' => 'Total',
        ];
    }
}
