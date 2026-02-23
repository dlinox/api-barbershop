<?php

namespace App\Modules\Administrator\Academy\Http\Requests\Room;

use App\Common\Http\Requests\ApiFormRequest;

class RoomRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id' => $id ? 'exists:academy_rooms,id' : 'nullable',
            'branch_id' => 'required|exists:academy_branches,id',
            'number' => 'required|integer',
            'description' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:1',
            'floor' => 'required|integer',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'El aula no existe',
            'branch_id.required' => 'La sucursal es requerida',
            'branch_id.exists' => 'La sucursal no existe',
            'number.required' => 'El número es requerido',
            'number.integer' => 'El número debe ser un entero',
            'description.string' => 'La descripción debe ser un texto',
            'description.max' => 'La descripción no debe exceder los 255 caracteres',
            'capacity.required' => 'La capacidad es requerida',
            'capacity.integer' => 'La capacidad debe ser un entero',
            'capacity.min' => 'La capacidad debe ser mayor o igual a 1',
            'floor.required' => 'El piso es requerido',
            'floor.integer' => 'El piso debe ser un entero',
            'is_active.required' => 'El estado es requerido',
            'is_active.boolean' => 'El estado debe ser un booleano',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'branch_id' => 'Sucursal',
            'number' => 'Número',
            'description' => 'Descripción',
            'capacity' => 'Capacidad',
            'floor' => 'Piso',
            'is_active' => 'Estado',
        ];
    }
}
