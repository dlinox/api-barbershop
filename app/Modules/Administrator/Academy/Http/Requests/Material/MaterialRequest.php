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
            'product_id' => 'required|integer|exists:inventory_products,id',
            'quantity' => 'required|integer|min:1',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'El material no existe',
            'product_id.required' => 'El producto es requerido',
            'product_id.integer' => 'El producto debe ser un entero',
            'product_id.exists' => 'El producto no existe',
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
            'product_id' => 'Producto',
            'quantity' => 'Cantidad',
            'is_active' => 'Estado',
        ];
    }
}
