<?php

namespace App\Modules\Administrator\Treasury\Http\Requests\Worker;

use App\Common\Http\Requests\ApiFormRequest;

class WorkerRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [

            'id' => ['nullable', 'integer'],

            'position' => ['required', 'string', 'max:50'],
            'monthly_salary' => ['required', 'numeric', 'min:0'],
            'payment_frequency' => ['nullable', 'string', 'in:monthly,biweekly'],
            'is_active' => ['required', 'boolean'],

            'person.id'  => ['nullable', 'integer'],
            'person.document_type' => ['required', 'integer'],
            'person.document_number' => ['required', 'max:20'],
            'person.name' => ['required', 'max:100'],
            'person.paternal_surname' => ['required_without:person.maternal_surname', 'nullable', 'max:80'],
            'person.maternal_surname' => ['required_without:person.paternal_surname', 'nullable', 'max:80'],
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
            'position.required' => 'El cargo es requerido',
            'position.max' => 'El cargo debe tener máximo 50 caracteres',
            'monthly_salary.required' => 'El sueldo mensual es requerido',
            'monthly_salary.numeric' => 'El sueldo mensual debe ser un número',
            'monthly_salary.min' => 'El sueldo mensual debe ser mayor o igual a 0',
            'payment_frequency.required' => 'La frecuencia de pago es requerida',
            'payment_frequency.in' => 'La frecuencia de pago debe ser mensual o quincenal',
            'is_active.required' => 'El estado del trabajador es requerido',
            'person.document_type.required' => 'Tipo de documento es requerido',
            'person.document_number.required' => 'Número de documento es requerido',
            'person.name.required' => 'Nombre es requerido',
            'person.paternal_surname.required_without' => 'Debe ingresar al menos un apellido (paterno o materno)',
            'person.maternal_surname.required_without' => 'Debe ingresar al menos un apellido (paterno o materno)',
            'person.phone.max' => 'Teléfono debe tener máximo 15 caracteres',
            'person.email.email' => 'Email es inválido',
            'person.email.max' => 'Email debe tener máximo 100 caracteres',
            'person.date_birth.date' => 'Fecha de nacimiento es inválida',
            'person.gender.max' => 'Género debe tener máximo 1 carácter',
            'person.address.max' => 'Dirección debe tener máximo 255 caracteres',
        ];
    }

    public function attributes(): array
    {
        return [
            'position' => 'Cargo',
            'monthly_salary' => 'Sueldo mensual',
            'payment_frequency' => 'Frecuencia de pago',
            'is_active' => 'Estado del trabajador',
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
