<?php

namespace App\Modules\Administrator\Setting\Http\Requests\DocumentType;

use App\Common\Http\Requests\ApiFormRequest;

class DocumentTypeRequest extends ApiFormRequest
{
    public function rules()
    {
        $code = $this->code ?? null;
        return [
            'code' => $code ? 'required|string|max:2|exists:core_document_types,code' : 'required|string|max:2|unique:core_document_types,code',
            'name' => 'required|string|max:100|unique:core_document_types,name,' . $code . ',code',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'El código es requerido',
            'code.string' => 'El código debe ser una cadena de texto',
            'code.max' => 'El código debe tener un máximo de 2 caracteres',
            'code.exists' => 'El tipo de documento no existe',
            'code.unique' => 'El código ya existe',
            'name.required' => 'El nombre es requerido',
            'name.string' => 'El nombre debe ser una cadena de texto',
            'name.max' => 'El nombre debe tener un máximo de 100 caracteres',
            'name.unique' => 'El nombre ya existe',
            'is_active.required' => 'El estado es requerido',
            'is_active.boolean' => 'El estado debe ser un booleano',
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'Código',
            'name' => 'Nombre',
            'is_active' => 'Estado',
        ];
    }
}
