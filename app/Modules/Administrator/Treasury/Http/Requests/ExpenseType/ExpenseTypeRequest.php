<?php

namespace App\Modules\Administrator\Treasury\Http\Requests\ExpenseType;

use App\Common\Http\Requests\ApiFormRequest;

class ExpenseTypeRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $id = $this->id ?? null;
        return [
            'id'          => $id ? 'exists:treasury_expense_types,id' : 'nullable',
            'name'        => 'required|string|max:255|unique:treasury_expense_types,name,' . $id,
            'description' => 'nullable|string|max:255',
            'is_active'   => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists'     => 'El tipo de gasto no existe',
            'name.required' => 'El nombre es requerido',
            'name.string'   => 'El nombre debe ser una cadena de texto',
            'name.max'      => 'El nombre debe tener un máximo de 255 caracteres',
            'name.unique'   => 'El nombre ya existe',
        ];
    }

    public function attributes(): array
    {
        return [
            'id'          => 'ID',
            'name'        => 'Nombre',
            'description' => 'Descripción',
        ];
    }
}
