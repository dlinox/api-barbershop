<?php

namespace App\Modules\Administrator\Setting\Http\Requests\Company;

use App\Common\Http\Requests\ApiFormRequest;

class CompanyRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;

        return [
            'id' => $id ? 'exists:core_companies,id' : 'nullable',
            'name' => 'required|string|max:200',
            'trade_name' => 'nullable|string|max:200',
            'ruc' => 'required|string|max:20|unique:core_companies,ruc,' . $id,
            'address' => 'nullable|string|max:300',
            'phone' => 'nullable|string|max:20',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'La empresa no existe',
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre debe tener un máximo de 200 caracteres',
            'trade_name.string' => 'El nombre comercial debe ser una cadena de texto',
            'trade_name.max' => 'El nombre comercial debe tener un máximo de 200 caracteres',
            'ruc.required' => 'El RUC es requerido',
            'ruc.string' => 'El RUC debe ser una cadena de texto',
            'ruc.max' => 'El RUC debe tener un máximo de 20 caracteres',
            'ruc.unique' => 'El RUC ya existe',
            'address.string' => 'La dirección debe ser una cadena de texto',
            'address.max' => 'La dirección debe tener un máximo de 300 caracteres',
            'phone.string' => 'El celular debe ser una cadena de texto',
            'phone.max' => 'El celular debe tener un máximo de 20 caracteres',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nombre',
            'trade_name' => 'Nombre comercial',
            'ruc' => 'RUC',
            'address' => 'Dirección',
            'phone' => 'Celular',
        ];
    }
}
