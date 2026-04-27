<?php

namespace App\Modules\BarbershopPanel\Profile\Http\Requests;

use App\Common\Http\Context\AdminContext;
use App\Modules\Administrator\Treasury\Http\Requests\Worker\WorkerRequest as BaseWorkerRequest;

class WorkerRequest extends BaseWorkerRequest
{
    protected function prepareForValidation(): void
    {
        parent::prepareForValidation();

        $this->merge([
            'infrastructure_id' => AdminContext::infrastructureId(),
        ]);
    }
}