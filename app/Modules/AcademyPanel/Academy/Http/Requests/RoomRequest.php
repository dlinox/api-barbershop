<?php

namespace App\Modules\AcademyPanel\Academy\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class RoomRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $id = $this->id ?? null;
        return [
            'id'          => $id ? 'exists:academy_rooms,id' : 'nullable',
            'number'      => 'required|integer',
            'description' => 'nullable|string|max:255',
            'capacity'    => 'required|integer|min:1',
            'floor'       => 'required|integer',
            'is_active'   => 'required|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'number'      => 'Número',
            'description' => 'Descripción',
            'capacity'    => 'Capacidad',
            'floor'       => 'Piso',
            'is_active'   => 'Estado',
        ];
    }
}
