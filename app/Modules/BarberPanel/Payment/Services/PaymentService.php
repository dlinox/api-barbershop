<?php

namespace App\Modules\BarberPanel\Payment\Services;

use App\Modules\BarberPanel\Payment\Repositories\PaymentRepository;
use App\Modules\BarberPanel\Shared\BarberContext;

class PaymentService
{
    public function __construct(
        private readonly PaymentRepository $repository,
    ) {}

    public function dataTable($request)
    {
        return $this->repository->dataTable($request, BarberContext::barberId());
    }
}
