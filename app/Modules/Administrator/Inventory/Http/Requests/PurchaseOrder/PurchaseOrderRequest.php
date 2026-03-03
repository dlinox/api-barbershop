<?php

namespace App\Modules\Administrator\Inventory\Http\Requests\PurchaseOrder;

use App\Common\Http\Requests\ApiFormRequest;

class PurchaseOrderRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id' => $id ? 'exists:inventory_purchase_orders,id' : 'nullable',
            'supplier_id' => 'required|exists:inventory_suppliers,id',
            'infrastructure_id' => 'required|exists:core_infrastructures,id',
            'order_number' => 'nullable|string|max:50',
            'order_date' => 'nullable|date',
            'expected_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.presentation_id' => 'required|exists:inventory_product_presentations,id',
            'items.*.quantity_ordered' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.subtotal' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_id.required' => 'El proveedor es requerido',
            'supplier_id.exists' => 'El proveedor seleccionado no existe',
            'infrastructure_id.required' => 'La sede es requerida',
            'infrastructure_id.exists' => 'La sede seleccionada no existe',
            'items.required' => 'Debe agregar al menos un producto',
            'items.min' => 'Debe agregar al menos un producto',
            'items.*.presentation_id.required' => 'La presentación es requerida',
            'items.*.presentation_id.exists' => 'La presentación seleccionada no existe',
            'items.*.quantity_ordered.required' => 'La cantidad es requerida',
            'items.*.quantity_ordered.min' => 'La cantidad debe ser al menos 1',
            'items.*.unit_price.required' => 'El precio unitario es requerido',
            'items.*.unit_price.min' => 'El precio unitario debe ser al menos 0',
            'items.*.subtotal.required' => 'El subtotal es requerido',
        ];
    }

    public function attributes(): array
    {
        return [
            'supplier_id' => 'Proveedor',
            'infrastructure_id' => 'Sede',
            'order_number' => 'Número de orden',
            'order_date' => 'Fecha de orden',
            'expected_date' => 'Fecha esperada',
            'notes' => 'Notas',
            'items' => 'Productos',
        ];
    }
}
