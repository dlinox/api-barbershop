<?php

namespace App\Modules\Administrator\Inventory\Http\Requests\Category;

use App\Common\Http\Requests\ApiFormRequest;

class CategoryRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id' => $id ? 'exists:inventory_categories,id' : 'nullable',
            'parent_id' => 'nullable|exists:inventory_categories,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:product,service',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'La categoría no existe',
            'parent_id.exists' => 'La categoría padre no existe',
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre debe tener un máximo de 255 caracteres',
            'type.required' => 'El tipo es requerido',
            'type.in' => 'El tipo debe ser producto o servicio',
            'icon.string' => 'El icono debe ser una cadena de texto',
            'is_active.required' => 'El estado es requerido',
            'is_active.boolean' => 'El estado debe ser un booleano',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Categoría padre',
            'name' => 'Nombre',
            'type' => 'Tipo',
            'icon' => 'Icono',
            'is_active' => 'Estado',
        ];
    }
}
