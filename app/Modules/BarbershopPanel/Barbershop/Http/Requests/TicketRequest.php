<?php

namespace App\Modules\BarbershopPanel\Barbershop\Http\Requests;

use App\Common\Http\Context\AdminContext;
use App\Modules\Administrator\Barbershop\Http\Requests\Ticket\TicketRequest as BaseTicketRequest;

class TicketRequest extends BaseTicketRequest
{
    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->merge([
            'branch_id' => AdminContext::barbershopBranchId(),
        ]);
    }
}
