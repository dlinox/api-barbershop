<?php

namespace App\Modules\Administrator\Treasury\Repositories;

use App\Models\Treasury\EmployeeAdvance;
use App\Models\Profile\Barber;
use App\Models\Profile\Teacher;
use App\Common\Traits\HasInfrastructureScope;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EmployeeAdvanceRepository
{
    use HasInfrastructureScope;

    public function dataTable($request)
    {
        $query = EmployeeAdvance::with([
            'employee' => function (MorphTo $morphTo) {
                $morphTo->morphWith([
                    Barber::class => ['branch'],
                    Teacher::class => ['branch'],
                ]);
            },
            'paymentMethod',
        ]);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        if (isset($data['id']) && $data['id']) {
            $advance = EmployeeAdvance::findOrFail($data['id']);

            if ($advance->status === 'discounted' || $advance->discounted_in_payment_id) {
                throw new \Exception('No se puede editar un adelanto que ya fue aplicado o descontado');
            }
        }

        return EmployeeAdvance::updateOrCreate(['id' => $data['id']], $data);
    }

    public function delete(int $id)
    {
        $advance = EmployeeAdvance::findOrFail($id);

        if ($advance->status === 'discounted' || $advance->discounted_in_payment_id) {
            throw new \Exception('No se puede eliminar un adelanto que ya fue aplicado o descontado');
        }

        $advance->delete();
        return $advance;
    }
}
