<?php

namespace App\Modules\Administrator\Inventory\Http\Requests\Supplier;

use App\Common\Http\Requests\ApiFormRequest;

class SupplierRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id' => $id ? 'exists:inventory_suppliers,id' : 'nullable',
            'name' => 'required|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'El proveedor no existe',
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre debe tener un máximo de 255 caracteres',
            'contact_name.string' => 'El nombre de contacto debe ser una cadena de texto',
            'phone.string' => 'El teléfono debe ser una cadena de texto',
            'phone.max' => 'El teléfono debe tener un máximo de 15 caracteres',
            'email.email' => 'El email debe ser un correo válido',
            'email.max' => 'El email debe tener un máximo de 100 caracteres',
            'address.string' => 'La dirección debe ser una cadena de texto',
            'is_active.required' => 'El estado es requerido',
            'is_active.boolean' => 'El estado debe ser un booleano',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Nombre',
            'contact_name' => 'Nombre de contacto',
            'phone' => 'Teléfono',
            'email' => 'Email',
            'address' => 'Dirección',
            'is_active' => 'Estado',
        ];
    }
}
