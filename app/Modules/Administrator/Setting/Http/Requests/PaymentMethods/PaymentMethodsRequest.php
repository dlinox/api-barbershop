<?php

namespace App\Modules\Administrator\Setting\Http\Requests\PaymentMethods;

use App\Common\Http\Requests\ApiFormRequest;

class PaymentMethodsRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id' => $id ? 'exists:core_payment_methods,id' : 'nullable',
            'name' => 'required|string|max:255|unique:core_payment_methods,name,' . $id,
            'type' => 'required|string|max:255',
            'is_active' => 'required|boolean',
            'is_default' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'El método de pago no existe',
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre debe tener un máximo de 255 caracteres',
            'name.unique' => 'El nombre ya existe',
            'type.required' => 'El tipo es requerido',
            'type.string' => 'El tipo debe ser una cadena de texto',
            'type.max' => 'El tipo debe tener un máximo de 255 caracteres',
            'is_active.required' => 'El estado es requerido',
            'is_active.boolean' => 'El estado debe ser un booleano',
            'is_default.required' => 'El valor por defecto es requerido',
            'is_default.boolean' => 'El valor por defecto debe ser un booleano',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Nombre',
            'type' => 'Tipo',
            'is_active' => 'Estado',
            'is_default' => 'Por defecto',
        ];
    }
}
