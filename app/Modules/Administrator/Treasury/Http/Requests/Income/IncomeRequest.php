<?php

namespace App\Modules\Administrator\Treasury\Http\Requests\Income;

use App\Common\Http\Requests\ApiFormRequest;

class IncomeRequest extends ApiFormRequest
{

    public function rules(): array
    {
        return [
            'id' => 'nullable|exists:treasury_incomes,id',
            'receipt_type' => 'required|string|in:00',
            'receipt_serie' => 'required|string|max:4',
            'observations' => 'nullable|string|max:255',
            'cash_register_id' => 'nullable|exists:treasury_cash_registers,id',
            'details' => 'required|array',
            'details.*.income_id' => 'nullable|exists:treasury_incomes,id',
            'details.*.description' => 'required|string|max:255',
            'details.*.unit_price' => 'required|numeric|min:0',
            'details.*.subtotal' => 'required|numeric|min:0',
            'details.*.quantity' => 'required|numeric|min:0',
            'details.*.discount' => 'required|numeric|min:0',
            'payment_methods' => 'required|array',
            'payment_methods.*.id' => 'required|exists:core_payment_methods,id',
            'payment_methods.*.amount' => 'required|numeric|min:0',
            'payment_methods.*.reference' => 'nullable|string|max:255',
            'client_id' => 'nullable|exists:core_persons,id',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'El identificador es requerido',
            'id.exists' => 'No se encontro el ingreso',
            'receipt_type.required' => 'El tipo de recibo es requerido',
            'receipt_type.in' => 'El tipo de recibo debe ser 00',
            'receipt_serie.required' => 'La serie del recibo es requerida',
            'receipt_serie.max' => 'La serie del recibo debe tener maximo 4 caracteres',
            'observations.required' => 'La observacion es requerida',
            'observations.max' => 'La observacion debe tener maximo 255 caracteres',
            'cash_register_id.required' => 'El identificador de la caja es requerido',
            'cash_register_id.exists' => 'No se encontro la caja',
            'details.required' => 'Los detalles son requeridos',
            'details.array' => 'Los detalles deben ser un array',
            'details.*.income_id.required' => 'El identificador del ingreso es requerido',
            'details.*.income_id.exists' => 'No se encontro el ingreso',
            'details.*.description.required' => 'La descripcion es requerida',
            'details.*.description.max' => 'La descripcion debe tener maximo 255 caracteres',
            'details.*.unit_price.required' => 'El precio unitario es requerido',
            'details.*.unit_price.numeric' => 'El precio unitario debe ser un numero',
            'details.*.unit_price.min' => 'El precio unitario debe ser mayor o igual a 0',
            'details.*.subtotal.required' => 'El subtotal es requerido',
            'details.*.subtotal.numeric' => 'El subtotal debe ser un numero',
            'details.*.subtotal.min' => 'El subtotal debe ser mayor o igual a 0',
            'details.*.quantity.required' => 'La cantidad es requerida',
            'details.*.quantity.numeric' => 'La cantidad debe ser un numero',
            'details.*.quantity.min' => 'La cantidad debe ser mayor o igual a 0',
            'details.*.discount.required' => 'El descuento es requerido',
            'details.*.discount.numeric' => 'El descuento debe ser un numero',
            'details.*.discount.min' => 'El descuento debe ser mayor o igual a 0',
            'payment_methods.required' => 'Los metodos de pago son requeridos',
            'payment_methods.array' => 'Los metodos de pago deben ser un array',
            'payment_methods.*.id.required' => 'El identificador del metodo de pago es requerido',
            'payment_methods.*.id.exists' => 'No se encontro el metodo de pago',
            'payment_methods.*.amount.required' => 'El monto es requerido',
            'payment_methods.*.amount.numeric' => 'El monto debe ser un numero',
            'payment_methods.*.amount.min' => 'El monto debe ser mayor o igual a 0',
            'payment_methods.*.reference.required' => 'La referencia es requerida',
            'payment_methods.*.reference.max' => 'La referencia debe tener maximo 255 caracteres',
            'client_id.exists' => 'No se encontro el cliente',
        ];
    }

    public function attributes()
    {
        return [
            'id' => 'identificador',
            'receipt_type' => 'tipo de recibo',
            'receipt_serie' => 'serie del recibo',
            'observations' => 'observacion',
            'cash_register_id' => 'identificador de la caja',
            'details' => 'detalles',
            'details.*.income_id' => 'identificador del ingreso',
            'details.*.description' => 'descripcion',
            'details.*.unit_price' => 'precio unitario',
            'details.*.subtotal' => 'subtotal',
            'details.*.quantity' => 'cantidad',
            'details.*.discount' => 'descuento',
            'client_id' => 'cliente',
            'payment_methods.*.id' => 'identificador del metodo de pago',
            'payment_methods.*.amount' => 'monto',
            'payment_methods.*.reference' => 'referencia',
        ];
    }
}
