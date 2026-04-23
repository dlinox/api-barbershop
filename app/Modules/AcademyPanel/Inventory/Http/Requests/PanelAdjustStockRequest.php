<?php

namespace App\Modules\AcademyPanel\Inventory\Http\Requests;

use App\Common\Http\Context\AdminContext;
use App\Modules\Administrator\Inventory\Http\Requests\Stock\AdjustStockRequest;

class PanelAdjustStockRequest extends AdjustStockRequest
{
    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();
        $this->merge([
            'infrastructure_id' => AdminContext::infrastructureId(),
        ]);
    }
}
