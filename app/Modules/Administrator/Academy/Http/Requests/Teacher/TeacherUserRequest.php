<?php

namespace App\Modules\Administrator\Academy\Http\Requests\Teacher;

use App\Common\Http\Requests\ApiFormRequest;

class TeacherUserRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer'],
            'user.id' => ['required', 'integer'],
            'user.username' => ['required', 'max:50'],
            'user.email' => ['nullable', 'email', 'max:100'],
            'user.password' => ['nullable', 'string', 'min:4'],
            'user.is_active' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'id.required' => 'El docente es requerido',
            'user.id.required' => 'El usuario es requerido',
            'user.username.required' => 'Usuario es requerido',
            'user.username.max' => 'Usuario debe tener máximo 50 caracteres',
            'user.email.email' => 'Email es inválido',
            'user.email.max' => 'Email debe tener máximo 100 caracteres',
            'user.is_active.required' => 'Estado es requerido',
        ];
    }
}
