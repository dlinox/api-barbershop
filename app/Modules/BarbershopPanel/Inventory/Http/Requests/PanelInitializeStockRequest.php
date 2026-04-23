<?php

namespace App\Modules\BarbershopPanel\Inventory\Http\Requests;

use App\Common\Http\Context\AdminContext;
use App\Modules\Administrator\Inventory\Http\Requests\Stock\InitializeStockRequest;

class PanelInitializeStockRequest extends InitializeStockRequest
{
    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();
        $this->merge([
            'infrastructure_id' => AdminContext::infrastructureId(),
        ]);
    }
}
