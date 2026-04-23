<?php

namespace App\Modules\Administrator\Barbershop\Http\Requests\Branch;

use App\Common\Http\Requests\ApiFormRequest;

class BranchRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id' => $id ? 'exists:barbershop_branches,id' : 'nullable',
            'name' => 'required|string|max:255|unique:barbershop_branches,name,' . $id,
            'address' => 'nullable|string|max:255',
            'ubication' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'location_lat' => 'nullable|numeric',
            'location_lng' => 'nullable|numeric',
            'logo' => 'nullable|string',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'La sucursal no existe',
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre debe tener un máximo de 255 caracteres',
            'name.unique' => 'El nombre ya existe',
            'address.string' => 'La dirección debe ser una cadena de texto',
            'address.max' => 'La dirección debe tener un máximo de 255 caracteres',
            'phone.max' => 'El teléfono debe tener un máximo de 20 caracteres',
            'is_active.required' => 'El estado es requerido',
            'is_active.boolean' => 'El estado debe ser un booleano',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Nombre',
            'address' => 'Dirección',
            'ubication' => 'Ubicación',
            'phone' => 'Teléfono',
            'location_lat' => 'Latitud',
            'location_lng' => 'Longitud',
            'is_active' => 'Estado',
        ];
    }
}
