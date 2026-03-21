<?php

namespace App\Modules\Administrator\Barbershop\Http\Requests\Client;

use App\Common\Http\Requests\ApiFormRequest;

class ClientRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [

            'id' => ['nullable', 'integer'],

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
