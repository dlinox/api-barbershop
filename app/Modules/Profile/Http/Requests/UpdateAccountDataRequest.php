<?php

namespace App\Modules\Profile\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class UpdateAccountDataRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'username' => 'nombre de usuario',
            'email' => 'correo electrónico',
        ];
    }
}
