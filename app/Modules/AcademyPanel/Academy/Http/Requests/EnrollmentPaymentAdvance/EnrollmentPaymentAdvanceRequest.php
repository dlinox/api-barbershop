<?php

namespace App\Modules\AcademyPanel\Academy\Http\Requests\EnrollmentPaymentAdvance;

use App\Common\Http\Requests\ApiFormRequest;

class EnrollmentPaymentAdvanceRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $id = $this->id ?? null;

        return [
            'id'           => $id ? 'exists:academy_enrollment_payment_advances,id' : 'nullable',
            'student_id'   => ['required', 'integer', 'exists:profile_students,core_person_id'],
            'amount'       => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'observation'  => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists'            => 'El adelanto no existe',
            'student_id.required'  => 'El estudiante es requerido',
            'student_id.exists'    => 'El estudiante no existe',
            'amount.required'      => 'El monto es requerido',
            'amount.numeric'       => 'El monto debe ser un número',
            'amount.min'           => 'El monto debe ser mayor a 0',
            'payment_date.required'=> 'La fecha de pago es requerida',
            'payment_date.date'    => 'La fecha de pago debe ser una fecha válida',
            'observation.max'      => 'La observación debe tener máximo 500 caracteres',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'           => 'ID',
            'student_id'   => 'Estudiante',
            'amount'       => 'Monto',
            'payment_date' => 'Fecha de pago',
            'observation'  => 'Observación',
        ];
    }
}
