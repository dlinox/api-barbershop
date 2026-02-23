<?php

namespace App\Modules\Administrator\Inventory\Http\Requests\Product;

use App\Common\Http\Requests\ApiFormRequest;

class ProductRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id' => $id ? 'exists:inventory_products,id' : 'nullable',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:inventory_categories,id',
            'brand_id' => 'nullable|exists:inventory_brands,id',
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'required|integer|min:0',
            'is_for_sale' => 'required|boolean',
            'is_for_internal' => 'required|boolean',
            'image_url' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'El producto no existe',

            'name.required' => 'El nombre es requerido',
            'name.max' => 'El nombre debe tener un máximo de 255 caracteres',
            'category_id.required' => 'La categoría es requerida',
            'category_id.exists' => 'La categoría no existe',
            'brand_id.exists' => 'La marca no existe',
            'min_stock.required' => 'El stock mínimo es requerido',
            'min_stock.integer' => 'El stock mínimo debe ser un número entero',
            'min_stock.min' => 'El stock mínimo debe ser mínimo 0',
            'max_stock.required' => 'El stock máximo es requerido',
            'max_stock.integer' => 'El stock máximo debe ser un número entero',
            'max_stock.min' => 'El stock máximo debe ser mínimo 0',
            'is_for_sale.required' => 'El campo de venta es requerido',
            'is_for_sale.boolean' => 'El campo de venta debe ser un booleano',
            'is_for_internal.required' => 'El campo de uso interno es requerido',
            'is_for_internal.boolean' => 'El campo de uso interno debe ser un booleano',
            'is_active.required' => 'El estado es requerido',
            'is_active.boolean' => 'El estado debe ser un booleano',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Nombre',
            'description' => 'Descripción',
            'category_id' => 'Categoría',
            'brand_id' => 'Marca',
            'min_stock' => 'Stock mínimo',
            'max_stock' => 'Stock máximo',
            'is_for_sale' => 'Para venta',
            'is_for_internal' => 'Uso interno',
            'image_url' => 'Imagen',
            'is_active' => 'Estado',
        ];
    }
}
