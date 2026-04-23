<?php

namespace App\Modules\BarbershopPanel\Inventory\Http\Requests;

use App\Common\Http\Context\AdminContext;
use App\Modules\Administrator\Inventory\Http\Requests\PurchaseOrder\PurchaseOrderRequest;

class PanelPurchaseOrderRequest extends PurchaseOrderRequest
{
    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();
        $this->merge([
            'infrastructure_id' => AdminContext::infrastructureId(),
        ]);
    }
}
