<?php

namespace App\Modules\BarberPanel\Ticket\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;
use App\Modules\Administrator\Treasury\Http\Requests\Income\IncomeRequest;

class TicketRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $id = $this->id ?? null;

        $rules = [
            'id'                           => $id ? 'exists:barbershop_tickets,id' : 'nullable',
            'cash_session_id'              => 'required|exists:treasury_cash_sessions,id',
            'client_id'                    => 'nullable|exists:core_persons,id',
            'reservation_id'               => 'nullable|exists:barbershop_reservations,id',

            'services'                     => 'required|array|min:1',
            'services.*.service_id'        => 'required|exists:barbershop_services,id',
            'services.*.quantity'          => 'nullable|integer|min:1',
            'services.*.amount'            => 'required|numeric|min:0',
            'services.*.discount'          => 'nullable|numeric|min:0',

            'products'                     => 'nullable|array',
            'products.*.presentation_id'   => 'required|exists:inventory_product_presentations,id',
            'products.*.quantity'          => 'required|integer|min:1',
            'products.*.unit_price'        => 'required|numeric|min:0',
            'products.*.discount'          => 'nullable|numeric|min:0',

            'income'                       => 'nullable|array',
        ];

        if ($this->has('income') && $this->income !== null) {
            $incomeRequest = new IncomeRequest();
            $incomeRules = collect($incomeRequest->rules())
                ->mapWithKeys(fn($rule, $key) => ["income.{$key}" => $rule])
                ->all();

            return array_merge($rules, $incomeRules);
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'cash_session_id'              => 'sesión de caja',
            'client_id'                    => 'cliente',
            'services'                     => 'servicios',
            'services.*.service_id'        => 'servicio',
            'services.*.quantity'          => 'cantidad',
            'services.*.amount'            => 'monto',
            'services.*.discount'          => 'descuento',
            'products'                     => 'productos',
            'products.*.presentation_id'   => 'presentación',
            'products.*.quantity'          => 'cantidad',
            'products.*.unit_price'        => 'precio unitario',
            'products.*.discount'          => 'descuento',
        ];
    }
}
