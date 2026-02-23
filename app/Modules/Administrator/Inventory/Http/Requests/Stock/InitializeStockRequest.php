<?php

namespace App\Modules\Administrator\Inventory\Http\Requests\Stock;

use App\Common\Http\Requests\ApiFormRequest;

class InitializeStockRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'product_id'        => 'required|exists:inventory_products,id',
            'infrastructure_id' => 'required|exists:core_infrastructures,id',
            'quantity'          => 'required|integer|min:1',
            'unit_cost'         => 'required|numeric|min:0',
            'notes'             => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required'        => 'El producto es requerido',
            'product_id.exists'          => 'El producto no existe',
            'infrastructure_id.required' => 'La sucursal es requerida',
            'infrastructure_id.exists'   => 'La sucursal no existe',
            'quantity.required'          => 'La cantidad es requerida',
            'quantity.integer'           => 'La cantidad debe ser un número entero',
            'quantity.min'               => 'La cantidad debe ser mínimo 1',
            'unit_cost.required'         => 'El costo unitario es requerido',
            'unit_cost.numeric'          => 'El costo unitario debe ser un número',
            'unit_cost.min'              => 'El costo unitario debe ser mínimo 0',
        ];
    }

    public function attributes(): array
    {
        return [
            'product_id'        => 'Producto',
            'infrastructure_id' => 'Sucursal',
            'quantity'          => 'Cantidad',
            'unit_cost'         => 'Costo unitario',
            'notes'             => 'Notas',
        ];
    }
}
