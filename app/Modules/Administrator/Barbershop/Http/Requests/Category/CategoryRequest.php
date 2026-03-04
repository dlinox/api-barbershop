<?php

namespace App\Modules\Administrator\Barbershop\Http\Requests\Category;

use App\Common\Http\Requests\ApiFormRequest;

class CategoryRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id' => $id ? 'exists:barbershop_categories,id' : 'nullable',
            'name' => 'required|string|max:255|unique:barbershop_categories,name,' . $id,
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'La categoría no existe',
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre debe tener un máximo de 255 caracteres',
            'name.unique' => 'El nombre ya existe',
            'description.string' => 'La descripción debe ser una cadena de texto',
            'description.max' => 'La descripción debe tener un máximo de 255 caracteres',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Nombre',
            'description' => 'Descripción',
        ];
    }
}
