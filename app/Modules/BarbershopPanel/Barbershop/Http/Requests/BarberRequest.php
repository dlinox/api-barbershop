<?php

namespace App\Modules\BarbershopPanel\Barbershop\Http\Requests;

use App\Common\Http\Context\AdminContext;
use App\Modules\Administrator\Barbershop\Http\Requests\Barber\BarberRequest as BaseBarberRequest;

class BarberRequest extends BaseBarberRequest
{
    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->merge([
            'branch_id' => AdminContext::barbershopBranchId(),
        ]);
    }
}

