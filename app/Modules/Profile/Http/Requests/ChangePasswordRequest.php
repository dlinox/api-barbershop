<?php

namespace App\Modules\Profile\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class ChangePasswordRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8'],
            'new_password_confirmation' => ['required', 'string', 'same:new_password'],
        ];
    }

    public function messages(): array
    {
        return [
            'new_password.min' => 'La nueva contraseña debe tener al menos 8 caracteres',
            'new_password_confirmation.same' => 'Las contraseñas no coinciden',
        ];
    }

    public function attributes(): array
    {
        return [
            'current_password' => 'contraseña actual',
            'new_password' => 'nueva contraseña',
            'new_password_confirmation' => 'confirmación de contraseña',
        ];
    }
}
