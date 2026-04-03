<?php

namespace App\Modules\Administrator\Academy\Http\Requests\Teacher;

use App\Common\Http\Requests\ApiFormRequest;

class TeacherRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [

            'id' => ['nullable', 'integer'],
            'branch_id' => ['required', 'integer', 'exists:academy_branches,id'],
            'payment_type' => ['required', 'in:hourly,monthly'],
            'monthly_salary' => ['nullable', 'required_if:payment_type,monthly', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],

            'person.id'  => ['nullable', 'integer'],
            'person.document_type' => ['required', 'integer'],
            'person.document_number' => ['required', 'max:20'],
            'person.name' => ['required', 'max:100'],
            'person.paternal_surname' => ['required_without:person.maternal_surname', 'nullable', 'max:80'],
            'person.maternal_surname' => ['required_without:person.paternal_surname', 'nullable', 'max:80'],
            // 'person.email' => ['nullable', 'email', 'max:100'],
            'person.phone' => ['nullable', 'max:15'],
            'person.email' => ['nullable', 'email', 'max:100'],
            'person.date_birth' => ['nullable', 'date'],
            'person.gender' => ['nullable', 'string', 'max:1'],
            'person.address' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.exists' => 'La sede seleccionada no existe',
            'branch_id.required' => 'La sede es requerida',
            'payment_type.required' => 'El tipo de pago es requerido',
            'monthly_salary.required_if' => 'El monto mensual es requerido cuando el tipo de pago es mensual',
            'is_active.required' => 'El estado del docente es requerido',

            'person.document_type.required' => 'Tipo de documento es requerido',
            'person.document_number.required' => 'Número de documento es requerido',
            'person.name.required' => 'Nombre es requerido',
            'person.paternal_surname.required_without' => 'Debe ingresar al menos un apellido (paterno o materno)',
            'person.maternal_surname.required_without' => 'Debe ingresar al menos un apellido (paterno o materno)',
            'person.phone.max' => 'Teléfono debe tener máximo 15 caracteres',
        ];
    }

    public function attributes(): array
    {
        return [
            'branch_id' => 'Sede',
            'payment_type' => 'Tipo de pago',
            'monthly_salary' => 'Monto mensual',
            'is_active' => 'Estado del docente',

            'person.document_type' => 'Tipo de documento',
            'person.document_number' => 'Número de documento',
            'person.name' => 'Nombre',
            'person.paternal_surname' => 'Apellido paterno',
            'person.maternal_surname' => 'Apellido materno',
            'person.email' => 'Email personal',
            'person.phone' => 'Teléfono',
        ];
    }
}
