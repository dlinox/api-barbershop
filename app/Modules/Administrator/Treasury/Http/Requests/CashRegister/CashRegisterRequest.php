<?php

namespace App\Modules\Administrator\Treasury\Http\Requests\CashRegister;

use App\Common\Http\Requests\ApiFormRequest;

class CashRegisterRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id'                => $id ? 'exists:treasury_cash_registers,id' : 'nullable',
            'infrastructure_id' => 'required|exists:core_infrastructures,id',
            'name'              => 'required|string|max:255',
            'is_active'         => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists'                => 'La caja no existe',
            'infrastructure_id.required' => 'La sede es requerida',
            'infrastructure_id.exists'   => 'La sede no existe',
            'name.required'            => 'El nombre es requerido',
            'name.string'              => 'El nombre debe ser una cadena de texto',
            'name.max'                 => 'El nombre debe tener un máximo de 255 caracteres',
            'is_active.required'       => 'El estado es requerido',
            'is_active.boolean'        => 'El estado debe ser un booleano',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'                => 'ID',
            'infrastructure_id' => 'Sede',
            'name'              => 'Nombre',
            'is_active'         => 'Estado',
        ];
    }
}
