<?php

namespace App\Modules\Administrator\Academy\Http\Requests\Branch;

use App\Common\Http\Requests\ApiFormRequest;

class BranchRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id' => $id ? 'exists:academy_branches,id' : 'nullable',
            'name' => 'required|string|max:255|unique:academy_branches,name,' . $id,
            'address' => 'required|string|max:255',
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
            'address.required' => 'La dirección es requerida',
            'address.string' => 'La dirección debe ser una cadena de texto',
            'address.max' => 'La dirección debe tener un máximo de 255 caracteres',
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
            'is_active' => 'Estado',
        ];
    }
}
