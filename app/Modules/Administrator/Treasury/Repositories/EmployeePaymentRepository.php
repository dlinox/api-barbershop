<?php

namespace App\Modules\Administrator\Treasury\Repositories;

use App\Models\Treasury\EmployeePayment;
use App\Models\Profile\Barber;
use App\Common\Traits\HasInfrastructureScope;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EmployeePaymentRepository
{
    use HasInfrastructureScope;

    public function dataTable($request)
    {
        $query = EmployeePayment::with([
            'employee' => function (MorphTo $morphTo) {
                $morphTo->morphWith([
                    Barber::class => ['branch'],
                ]);
            },
            'paymentMethod',
            'paidBy',
        ]);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id']) && $data['id']) {
            $payment = EmployeePayment::findOrFail($data['id']);

            if ($payment->status === 'cancelled') {
                throw new \Exception('No se puede editar un pago cancelado');
            }
        }

        return EmployeePayment::updateOrCreate(['id' => $data['id']], $data);
    }

    public function delete(int $id)
    {
        $payment = EmployeePayment::findOrFail($id);

        if ($payment->status === 'cancelled') {
            throw new \Exception('No se puede eliminar un pago cancelado');
        }

        $payment->delete();
        return $payment;
    }
}
