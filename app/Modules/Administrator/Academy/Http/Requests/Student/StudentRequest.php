<?php

namespace App\Modules\Administrator\Academy\Http\Requests\Student;

use App\Common\Http\Requests\ApiFormRequest;

class StudentRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [

            'id' => ['nullable', 'integer'],

            'person.id'  => ['nullable', 'integer'],
            'person.document_type' => ['required', 'integer'],
            'person.document_number' => ['required', 'string', 'max:20'],
            'person.name' => ['required', 'string', 'max:100'],
            'person.paternal_surname' => ['nullable', 'string', 'max:80'],
            'person.maternal_surname' => ['nullable', 'string', 'max:80'],
            'person.email' => ['nullable', 'email', 'max:100'],
            'person.phone' => ['nullable', 'string', 'max:15'], //email personal

            'user.id' => ['nullable', 'integer'],
            'user.username' => ['required', 'string', 'max:50'],
            'user.email' => ['required', 'email', 'max:100'],
            'user.is_active' => ['required', 'boolean'],

        ];
    }

    public function messages(): array
    {
        return [
            'person.document_type.required' => 'Tipo de documento es requerido',
            'person.document_number.required' => 'Número de documento es requerido',
            'person.name.required' => 'Nombre es requerido',
            'person.paternal_surname.required' => 'Apellido paterno es requerido',
            'person.maternal_surname.required' => 'Apellido materno es requerido',
            'person.email.email' => 'Email es inválido',
            'person.email.max' => 'Email debe tener máximo 100 caracteres',
            'person.phone.max' => 'Teléfono debe tener máximo 15 caracteres',


            'user.username.required' => 'Usuario es requerido',
            'user.username.max' => 'Usuario debe tener máximo 50 caracteres',
            'user.email.required' => 'Email es requerido',
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
            'person.email' => 'Email personal',
            'person.phone' => 'Teléfono',

            'user.username' => 'Usuario',
            'user.email' => 'Email',
            'user.is_active' => 'Estado',
        ];
    }
}
