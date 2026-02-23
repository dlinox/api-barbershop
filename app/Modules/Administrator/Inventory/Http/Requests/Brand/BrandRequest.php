<?php

namespace App\Modules\Administrator\Inventory\Http\Requests\Brand;

use App\Common\Http\Requests\ApiFormRequest;

class BrandRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id' => $id ? 'exists:inventory_brands,id' : 'nullable',
            'name' => 'required|string|max:255|unique:inventory_brands,name,' . $id,
            'logo_url' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'La marca no existe',
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre debe tener un máximo de 255 caracteres',
            'name.unique' => 'El nombre ya existe',
            'logo_url.string' => 'La URL del logo debe ser una cadena de texto',
            'is_active.required' => 'El estado es requerido',
            'is_active.boolean' => 'El estado debe ser un booleano',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Nombre',
            'logo_url' => 'Logo',
            'is_active' => 'Estado',
        ];
    }
}
