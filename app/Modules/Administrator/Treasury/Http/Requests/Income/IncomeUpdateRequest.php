<?php

namespace App\Modules\Administrator\Treasury\Http\Requests\Income;

use App\Common\Http\Requests\ApiFormRequest;

class IncomeUpdateRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'edit_description'               => 'required|string|max:500',
            'details'                        => 'required|array|min:1',
            'details.*.id'                   => 'required|exists:treasury_income_details,id',
            'details.*.description'          => 'required|string|max:255',
            'details.*.quantity'             => 'required|numeric|min:0',
            'details.*.unit_price'           => 'required|numeric|min:0',
            'details.*.discount'             => 'required|numeric|min:0',
            'payment_methods'                        => 'required|array|min:1',
            'payment_methods.*.payment_method_id'    => 'required|exists:core_payment_methods,id',
            'payment_methods.*.amount'               => 'required|numeric|min:0',
            'payment_methods.*.payment_reference'    => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'edit_description.required'                => 'El motivo de edición es requerido',
            'edit_description.max'                     => 'El motivo debe tener máximo 500 caracteres',
            'details.required'                         => 'Los detalles son requeridos',
            'details.array'                            => 'Los detalles deben ser un arreglo',
            'details.min'                              => 'Debe haber al menos un detalle',
            'details.*.id.required'                    => 'El ID del detalle es requerido',
            'details.*.id.exists'                      => 'El detalle no existe',
            'details.*.description.required'           => 'La descripción es requerida',
            'details.*.description.max'                => 'La descripción debe tener máximo 255 caracteres',
            'details.*.quantity.required'              => 'La cantidad es requerida',
            'details.*.quantity.numeric'               => 'La cantidad debe ser un número',
            'details.*.quantity.min'                   => 'La cantidad debe ser mayor o igual a 0',
            'details.*.unit_price.required'            => 'El precio unitario es requerido',
            'details.*.unit_price.numeric'             => 'El precio unitario debe ser un número',
            'details.*.unit_price.min'                 => 'El precio unitario debe ser mayor o igual a 0',
            'details.*.discount.required'              => 'El descuento es requerido',
            'details.*.discount.numeric'               => 'El descuento debe ser un número',
            'details.*.discount.min'                   => 'El descuento debe ser mayor o igual a 0',
            'payment_methods.required'                              => 'Los métodos de pago son requeridos',
            'payment_methods.array'                                => 'Los métodos de pago deben ser un arreglo',
            'payment_methods.min'                                  => 'Debe haber al menos un método de pago',
            'payment_methods.*.payment_method_id.required'         => 'El método de pago es requerido',
            'payment_methods.*.payment_method_id.exists'           => 'El método de pago no existe',
            'payment_methods.*.amount.required'                    => 'El monto es requerido',
            'payment_methods.*.amount.numeric'                     => 'El monto debe ser un número',
            'payment_methods.*.amount.min'                         => 'El monto debe ser mayor o igual a 0',
            'payment_methods.*.payment_reference.max'              => 'La referencia debe tener máximo 255 caracteres',
        ];
    }

    public function attributes(): array
    {
        return [
            'edit_description'                 => 'motivo de edición',
            'details'                          => 'detalles',
            'details.*.id'                     => 'ID del detalle',
            'details.*.description'            => 'descripción',
            'details.*.quantity'               => 'cantidad',
            'details.*.unit_price'             => 'precio unitario',
            'details.*.discount'               => 'descuento',
            'payment_methods'                              => 'métodos de pago',
            'payment_methods.*.payment_method_id'         => 'método de pago',
            'payment_methods.*.amount'                    => 'monto',
            'payment_methods.*.payment_reference'         => 'referencia de pago',
        ];
    }
}
