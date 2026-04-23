<?php

namespace App\Modules\Administrator\Profile\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class WorkerRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id'                => ['nullable', 'integer'],
            'infrastructure_id' => ['nullable', 'integer'],
            'position'          => ['nullable', 'string', 'max:100'],
            'monthly_salary'    => ['nullable', 'numeric', 'min:0'],
            'payment_frequency' => ['nullable', 'string', 'in:monthly,biweekly'],
            'is_active'         => ['required', 'boolean'],

            'person.id'                  => ['nullable', 'integer'],
            'person.document_type'       => ['required', 'integer'],
            'person.document_number'     => ['required', 'max:20'],
            'person.name'                => ['required', 'max:100'],
            'person.paternal_surname'    => ['required_without:person.maternal_surname', 'nullable', 'max:80'],
            'person.maternal_surname'    => ['required_without:person.paternal_surname', 'nullable', 'max:80'],
            'person.phone'               => ['nullable', 'max:15'],
            'person.email'               => ['nullable', 'email', 'max:100'],
            'person.date_birth'          => ['nullable', 'date'],
            'person.gender'              => ['nullable', 'string', 'max:1'],
            'person.address'             => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'person.paternal_surname.required_without' => 'Debe ingresar al menos un apellido (paterno o materno)',
            'person.maternal_surname.required_without' => 'Debe ingresar al menos un apellido (paterno o materno)',
        ];
    }

    public function attributes(): array
    {
        return [
            'infrastructure_id'          => 'Sede',
            'position'                   => 'Cargo',
            'monthly_salary'             => 'Salario mensual',
            'payment_frequency'          => 'Frecuencia de pago',
            'person.document_type'       => 'Tipo de documento',
            'person.document_number'     => 'Número de documento',
            'person.name'                => 'Nombre',
            'person.paternal_surname'    => 'Apellido paterno',
            'person.maternal_surname'    => 'Apellido materno',
            'person.phone'               => 'Teléfono',
        ];
    }
}
