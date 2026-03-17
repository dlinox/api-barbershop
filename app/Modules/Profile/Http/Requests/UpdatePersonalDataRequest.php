<?php

namespace App\Modules\Profile\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class UpdatePersonalDataRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'paternal_surname' => ['nullable', 'string', 'max:100'],
            'maternal_surname' => ['nullable', 'string', 'max:100'],
            'document_type' => ['required', 'string'],
            'document_number' => ['required', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre',
            'paternal_surname' => 'apellido paterno',
            'maternal_surname' => 'apellido materno',
            'document_type' => 'tipo de documento',
            'document_number' => 'número de documento',
            'phone' => 'teléfono',
        ];
    }
}
