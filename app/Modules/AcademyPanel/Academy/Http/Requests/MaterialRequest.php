<?php

namespace App\Modules\AcademyPanel\Academy\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class MaterialRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $id = $this->id ?? null;
        return [
            'id'              => $id ? 'exists:academy_materials,id' : 'nullable',
            'presentation_id' => 'required|integer|exists:inventory_product_presentations,id',
            'quantity'        => 'required|integer|min:1',
            'is_active'       => 'required|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'presentation_id' => 'Presentación',
            'quantity'        => 'Cantidad',
            'is_active'       => 'Estado',
        ];
    }
}
