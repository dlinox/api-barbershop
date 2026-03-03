<?php

namespace App\Modules\Administrator\Inventory\Http\Requests\PurchaseOrder;

use App\Common\Http\Requests\ApiFormRequest;

class PurchaseOrderReceiveRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'receipt_type' => 'nullable|string|max:50',
            'receipt_serie' => 'nullable|string|max:4',
            'receipt_number' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'receipt_type.max' => 'El tipo de comprobante debe tener un máximo de 50 caracteres',
            'receipt_serie.max' => 'La serie debe tener un máximo de 4 caracteres',
            'receipt_number.integer' => 'El número de comprobante debe ser un número entero',
        ];
    }

    public function attributes(): array
    {
        return [
            'receipt_type' => 'Tipo de comprobante',
            'receipt_serie' => 'Serie de comprobante',
            'receipt_number' => 'Número de comprobante',
        ];
    }
}
