<?php

namespace App\Modules\Administrator\Inventory\Http\Requests\Sale;

use App\Common\Http\Requests\ApiFormRequest;
use App\Modules\Administrator\Treasury\Http\Requests\Income\IncomeRequest;

class SaleRequest extends ApiFormRequest
{
    public function rules(): array
    {
        //si income es null no se aplican las reglas de income, por eso se agrega el nullable

        if ($this->input('income') === null) {
            $incomeRules = [];
        } else {
            $incomeRequest = new IncomeRequest();
            $incomeRules = collect($incomeRequest->rules())
                ->mapWithKeys(fn($rule, $key) => ["income.{$key}" => $rule])
                ->all();
        }

        $saleRules = [
            'id'                            => 'nullable|exists:inventory_sales,id',
            'cash_session_id'              => 'required|exists:treasury_cash_sessions,id',
            'client_id'                    => 'nullable|exists:core_persons,id',
            'ticket_id'                    => 'nullable|exists:barbershop_tickets,id',
            'context'                      => 'required|string|in:barbershop,academy,other',
            'items'                        => 'required|array|min:1',
            'items.*.presentation_id'      => 'required|exists:inventory_product_presentations,id',
            'items.*.quantity'             => 'required|integer|min:1',
            'items.*.unit_price'           => 'required|numeric|min:0',
            'items.*.discount'             => 'nullable|numeric|min:0',
        ];

        return array_merge($saleRules, $incomeRules);
    }

    public function messages(): array
    {
        $incomeRequest = new IncomeRequest();
        $incomeMessages = collect($incomeRequest->messages())
            ->mapWithKeys(fn($message, $key) => ["income.{$key}" => $message])
            ->all();

        return array_merge([
            'cash_session_id.required'             => 'La sesión de caja es requerida',
            'cash_session_id.exists'               => 'La sesión de caja no existe',
            'ticket_id.exists'                     => 'El ticket no existe',
            'context.required'                     => 'El contexto es requerido',
            'context.in'                           => 'El contexto no es válido',
            'items.required'                       => 'Los productos son requeridos',
            'items.min'                            => 'Debe agregar al menos un producto',
            'items.*.presentation_id.required'     => 'La presentación es requerida',
            'items.*.presentation_id.exists'       => 'La presentación no existe',
            'items.*.quantity.required'            => 'La cantidad es requerida',
            'items.*.quantity.min'                 => 'La cantidad debe ser al menos 1',
            'items.*.unit_price.required'          => 'El precio unitario es requerido',
            'items.*.unit_price.min'               => 'El precio unitario debe ser mayor o igual a 0',
            'items.*.discount.min'                 => 'El descuento debe ser mayor o igual a 0',
        ], $incomeMessages);
    }

    public function attributes(): array
    {
        $incomeRequest = new IncomeRequest();
        $incomeAttributes = collect($incomeRequest->attributes())
            ->mapWithKeys(fn($attribute, $key) => ["income.{$key}" => $attribute])
            ->all();

        return array_merge([
            'cash_session_id'          => 'sesión de caja',
            'client_id'                => 'cliente',
            'ticket_id'                => 'ticket',
            'context'                  => 'contexto',
            'items'                    => 'productos',
            'items.*.presentation_id'  => 'presentación',
            'items.*.quantity'         => 'cantidad',
            'items.*.unit_price'       => 'precio unitario',
            'items.*.discount'         => 'descuento',
        ], $incomeAttributes);
    }
}
