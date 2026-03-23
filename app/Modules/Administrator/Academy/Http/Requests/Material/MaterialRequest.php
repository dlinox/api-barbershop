<?php

namespace App\Modules\Administrator\Academy\Http\Requests\Material;

use App\Common\Http\Requests\ApiFormRequest;

class MaterialRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id' => $id ? 'exists:academy_materials,id' : 'nullable',
            'branch_id' => 'required|integer|exists:academy_branches,id',
            'presentation_id' => 'required|integer|exists:inventory_product_presentations,id',
            'quantity' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'El material no existe',
            'branch_id.required' => 'La sede es requerida',
            'branch_id.integer' => 'La sede debe ser un entero',
            'branch_id.exists' => 'La sede no existe',
            'presentation_id.required' => 'La presentación es requerida',
            'presentation_id.integer' => 'La presentación debe ser un entero',
            'presentation_id.exists' => 'La presentación no existe',
            'quantity.required' => 'La cantidad es requerida',
            'quantity.integer' => 'La cantidad debe ser un entero',
            'quantity.min' => 'La cantidad debe ser mayor o igual a 1',
            'is_active.required' => 'El estado es requerido',
            'is_active.boolean' => 'El estado debe ser un booleano',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'branch_id' => 'Sede',
            'presentation_id' => 'Presentación',
            'quantity' => 'Cantidad',
            'is_active' => 'Estado',
        ];
    }
}
