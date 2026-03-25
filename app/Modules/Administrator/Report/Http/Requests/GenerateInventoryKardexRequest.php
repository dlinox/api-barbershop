<?php

namespace App\Modules\Administrator\Report\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class GenerateInventoryKardexRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'presentation_id'   => ['required', 'integer', 'exists:inventory_product_presentations,id'],
            'infrastructure_id' => ['required', 'integer', 'exists:core_infrastructures,id'],
            'date_from'         => ['required', 'date'],
            'date_to'           => ['required', 'date', 'after_or_equal:date_from'],
        ];
    }
}
