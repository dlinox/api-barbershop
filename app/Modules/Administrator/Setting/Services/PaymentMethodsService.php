<?php

namespace App\Modules\Administrator\Setting\Services;

use App\Modules\Administrator\Setting\Repositories\PaymentMethodsRepository;
use Illuminate\Http\Request;

class PaymentMethodsService
{
    public function __construct(
        private PaymentMethodsRepository $paymentMethodsRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->paymentMethodsRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->paymentMethodsRepository->createOrUpdate($data);
    }

    public function getActivePaymentMethods()
    {
        return $this->paymentMethodsRepository->getActivePaymentMethods();
    }

    public function delete(int $id)
    {
        return $this->paymentMethodsRepository->delete($id);
    }
}
