<?php

namespace App\Modules\Administrator\Barbershop\Http\Requests\Service;

use App\Common\Http\Requests\ApiFormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id' => $id ? 'exists:barbershop_services,id' : 'nullable',
            'name' => [
                'required', 'string', 'max:255',
                Rule::unique('barbershop_services', 'name')
                    ->where('branch_id', $this->branch_id)
                    ->ignore($id),
            ],
            'description' => 'nullable|string|max:255',
            'category_id' => 'required|exists:barbershop_categories,id',

            //service branch
            'branch_id' => 'required|exists:barbershop_branches,id',
            'price' => 'required|integer|min:0',
            'duration' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'El servicio no existe',
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre debe tener un máximo de 255 caracteres',
            'name.unique' => 'El nombre ya existe en esta sede',
            'description.string' => 'La descripción debe ser una cadena de texto',
            'description.max' => 'La descripción debe tener un máximo de 255 caracteres',
            'category_id.required' => 'La categoría es requerida',
            'category_id.exists' => 'La categoría no existe',
            'branch_id.required' => 'La sucursal es requerida',
            'branch_id.exists' => 'La sucursal no existe',
            'price.required' => 'El precio es requerido',
            'price.integer' => 'El precio debe ser un número entero',
            'price.min' => 'El precio debe ser mayor o igual a 0',
            'duration.required' => 'La duración es requerida',
            'duration.integer' => 'La duración debe ser un número entero',
            'duration.min' => 'La duración debe ser mayor o igual a 0',
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
            'branch_id' => 'Sucursal',
            'price' => 'Precio',
            'duration' => 'Duración',
            'is_active' => 'Estado',
        ];
    }
}
