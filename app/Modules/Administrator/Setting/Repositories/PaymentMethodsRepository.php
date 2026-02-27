<?php

namespace App\Modules\Administrator\Setting\Repositories;

use App\Models\Core\PaymentMethods;

class PaymentMethodsRepository
{
    public function dataTable($request)
    {
        return PaymentMethods::dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (!empty($data['is_default'])) {
            PaymentMethods::where('is_default', true)->update(['is_default' => false]);
        }

        return PaymentMethods::updateOrCreate(['id' => $data['id']], $data);
    }

    public function delete(int $id)
    {
        $paymentMethod = PaymentMethods::findOrFail($id);
        $paymentMethod->delete();
        return $paymentMethod;
    }

    public function getActivePaymentMethods()
    {
        return PaymentMethods::where('is_active', true)->get();
    }
}
