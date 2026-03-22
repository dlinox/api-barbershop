<?php

namespace App\Modules\Administrator\Setting\Http\Requests\Company;

use App\Common\Http\Requests\ApiFormRequest;

class CompanyLogoRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'logo' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'logo.required' => 'La imagen es requerida',
            'logo.string' => 'La imagen debe ser una cadena de texto',
        ];
    }

    public function attributes(): array
    {
        return [
            'logo' => 'Logo',
        ];
    }
}
