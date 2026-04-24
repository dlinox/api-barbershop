<?php

namespace App\Modules\BarberPanel\Pos\Http\Requests;

use App\Common\Http\Requests\ApiFormRequest;

class SaleRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $incomeRules = [];

        if ($this->input('income') !== null) {
            $incomeRules = [
                'income.receipt_type'                   => 'required|string|in:00',
                'income.receipt_serie'                  => 'required|string|max:4',
                'income.observations'                   => 'nullable|string|max:255',
                'income.cash_session_id'                => 'nullable|exists:treasury_cash_sessions,id',
                'income.details'                        => 'required|array',
                'income.details.*.description'          => 'required|string|max:255',
                'income.details.*.unit_price'           => 'required|numeric|min:0',
                'income.details.*.subtotal'             => 'required|numeric|min:0',
                'income.details.*.quantity'             => 'required|numeric|min:0',
                'income.details.*.discount'             => 'required|numeric|min:0',
                'income.payment_methods'                => 'required|array',
                'income.payment_methods.*.id'           => 'required|exists:core_payment_methods,id',
                'income.payment_methods.*.amount'       => 'required|numeric|min:0',
                'income.payment_methods.*.reference'    => 'nullable|string|max:255',
                'income.client_id'                      => 'nullable|exists:core_persons,id',
            ];
        }

        return array_merge([
            'id'                            => 'nullable|exists:inventory_sales,id',
            'cash_session_id'               => 'required|exists:treasury_cash_sessions,id',
            'client_id'                     => 'nullable|exists:core_persons,id',
            'ticket_id'                     => 'nullable|exists:barbershop_tickets,id',
            'context'                       => 'required|string|in:barbershop,academy,other',
            'items'                         => 'required|array|min:1',
            'items.*.presentation_id'       => 'required|exists:inventory_product_presentations,id',
            'items.*.quantity'              => 'required|integer|min:1',
            'items.*.unit_price'            => 'required|numeric|min:0',
            'items.*.discount'              => 'nullable|numeric|min:0',
        ], $incomeRules);
    }

    public function attributes(): array
    {
        return [
            'cash_session_id'          => 'sesión de caja',
            'client_id'                => 'cliente',
            'ticket_id'                => 'ticket',
            'context'                  => 'contexto',
            'items'                    => 'productos',
            'items.*.presentation_id'  => 'presentación',
            'items.*.quantity'         => 'cantidad',
            'items.*.unit_price'       => 'precio unitario',
            'items.*.discount'         => 'descuento',
        ];
    }
}
