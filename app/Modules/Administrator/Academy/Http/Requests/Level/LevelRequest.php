<?php

namespace App\Modules\Administrator\Academy\Http\Requests\Level;

use App\Common\Http\Requests\ApiFormRequest;

class LevelRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id' => $id ? 'exists:academy_levels,id' : 'nullable',
            'order' => 'required|integer',
            'name' => 'required|string|max:255|unique:academy_levels,name,' . $id,
            'description' => 'required|string|max:255',
            'duration_months' => 'required|integer',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'El nivel no existe',
            'order.required' => 'El orden es requerido',
            'order.integer' => 'El orden debe ser un número entero',
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre debe tener un máximo de 255 caracteres',
            'name.unique' => 'El nombre ya existe',
            'description.required' => 'La descripción es requerida',
            'description.string' => 'La descripción debe ser una cadena de texto',
            'description.max' => 'La descripción debe tener un máximo de 255 caracteres',
            'duration_months.required' => 'La duración es requerida',
            'duration_months.integer' => 'La duración debe ser un número entero',
            'is_active.required' => 'El estado es requerido',
            'is_active.boolean' => 'El estado debe ser un booleano',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'order' => 'Orden',
            'name' => 'Nombre',
            'description' => 'Descripción',
            'duration_months' => 'Duración (meses)',
            'is_active' => 'Estado',
        ];
    }
}
