<?php

namespace App\Modules\Administrator\Barbershop\Http\Requests\Barber;

use App\Common\Http\Requests\ApiFormRequest;

class BarberRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['nullable', 'integer'],
            'branch_id' => ['required', 'integer'],
            'commission_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'is_active' => ['required', 'boolean'],

            'person.id'  => ['nullable', 'integer'],
            'person.document_type' => ['required', 'integer'],
            'person.document_number' => ['required', 'max:20'],
            'person.name' => ['required', 'max:100'],
            'person.paternal_surname' => ['required_without:person.maternal_surname', 'nullable', 'max:80'],
            'person.maternal_surname' => ['required_without:person.paternal_surname', 'nullable', 'max:80'],
            'person.phone' => ['nullable', 'max:15'],

            'user.id' => ['nullable', 'integer'],
            'user.username' => ['required', 'max:50'],
            'user.email' => ['nullable', 'email', 'max:100'],
            'user.is_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'person.document_type.required' => 'Tipo de documento es requerido',
            'person.document_number.required' => 'Número de documento es requerido',
            'person.name.required' => 'Nombre es requerido',
            'person.paternal_surname.required_without' => 'Debe ingresar al menos un apellido (paterno o materno)',
            'person.maternal_surname.required_without' => 'Debe ingresar al menos un apellido (paterno o materno)',
            'person.phone.max' => 'Teléfono debe tener máximo 15 caracteres',

            'user.username.required' => 'Usuario es requerido',
            'user.username.max' => 'Usuario debe tener máximo 50 caracteres',
            'user.email.email' => 'Email es inválido',
            'user.email.max' => 'Email debe tener máximo 100 caracteres',
            'user.is_active.required' => 'Estado es requerido',
        ];
    }

    public function attributes(): array
    {
        return [
            'person.document_type' => 'Tipo de documento',
            'person.document_number' => 'Número de documento',
            'person.name' => 'Nombre',
            'person.paternal_surname' => 'Apellido paterno',
            'person.maternal_surname' => 'Apellido materno',
            'person.phone' => 'Teléfono',

            'user.username' => 'Usuario',
            'user.email' => 'Email',
            'user.is_active' => 'Estado',
        ];
    }
}
