<?php

namespace App\Modules\Administrator\Inventory\Http\Requests\Product;

use App\Common\Http\Requests\ApiFormRequest;

class ProductPresentationRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id' => $id ? 'exists:inventory_product_presentations,id' : 'nullable',
            'product_id' => 'required|exists:inventory_products,id',
            'name' => 'required|string|max:255',
            'unit_type' => 'required|in:unit,box,pack,bottle,tube,blister,bag,display,dozen',
            'quantity' => 'required|integer|min:1',
            'barcode' => 'nullable|string|max:100|unique:inventory_product_presentations,barcode,' . $id,
            'cost_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'is_default' => 'required|boolean',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'La presentación no existe',
            'product_id.required' => 'El producto es requerido',
            'product_id.exists' => 'El producto no existe',
            'name.required' => 'El nombre es requerido',
            'name.max' => 'El nombre debe tener un máximo de 255 caracteres',
            'unit_type.required' => 'El tipo de unidad es requerido',
            'unit_type.in' => 'El tipo de unidad no es válido',
            'quantity.required' => 'La cantidad es requerida',
            'quantity.integer' => 'La cantidad debe ser un número entero',
            'quantity.min' => 'La cantidad debe ser mínimo 1',
            'barcode.unique' => 'El código de barras ya existe',
            'barcode.max' => 'El código de barras debe tener un máximo de 100 caracteres',
            'cost_price.required' => 'El precio de compra es requerido',
            'cost_price.numeric' => 'El precio de compra debe ser un número',
            'cost_price.min' => 'El precio de compra debe ser mínimo 0',
            'sale_price.required' => 'El precio de venta es requerido',
            'sale_price.numeric' => 'El precio de venta debe ser un número',
            'sale_price.min' => 'El precio de venta debe ser mínimo 0',
            'is_default.required' => 'El campo predeterminado es requerido',
            'is_default.boolean' => 'El campo predeterminado debe ser un booleano',
            'is_active.required' => 'El estado es requerido',
            'is_active.boolean' => 'El estado debe ser un booleano',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'product_id' => 'Producto',
            'name' => 'Nombre',
            'unit_type' => 'Tipo de unidad',
            'quantity' => 'Cantidad',
            'barcode' => 'Código de barras',
            'cost_price' => 'Precio de compra',
            'sale_price' => 'Precio de venta',
            'is_default' => 'Predeterminado',
            'is_active' => 'Estado',
        ];
    }
}
