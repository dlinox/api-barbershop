<?php

namespace App\Modules\Administrator\Report\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class GenerateInventoryStockByProductRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'infrastructure_id' => ['nullable', 'integer', 'exists:core_infrastructures,id'],
        ];
    }
}
