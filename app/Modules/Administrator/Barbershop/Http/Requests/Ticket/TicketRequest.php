<?php

namespace App\Modules\Administrator\Barbershop\Http\Requests\Ticket;

use App\Common\Http\Requests\ApiFormRequest;
use App\Modules\Administrator\Treasury\Http\Requests\Income\IncomeRequest;

class TicketRequest extends ApiFormRequest
{
    public function rules(): array
    {
        $id = $this->id ?? null;

        $rules = [
            'id'                           => $id ? 'exists:barbershop_tickets,id' : 'nullable',
            'branch_id'                    => 'required|exists:barbershop_branches,id',
            'reservation_id'               => 'nullable|exists:barbershop_reservations,id',
            'cash_session_id'              => 'required|exists:treasury_cash_sessions,id',
            'barber_id'                    => 'nullable|exists:profile_barbers,id',
            'client_id'                    => 'nullable|exists:core_persons,id',

            'services'                     => 'required|array|min:1',
            'services.*.service_branch_id' => 'required|exists:barbershop_service_branches,id',
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

        // Si se envía income, es una confirmación con pago
        if ($this->has('income') && $this->income !== null) {
            $incomeRequest = new IncomeRequest();
            $incomeRules = collect($incomeRequest->rules())
                ->mapWithKeys(fn($rule, $key) => ["income.{$key}" => $rule])
                ->all();

            return array_merge($rules, $incomeRules);
        }

        return $rules;
    }

    public function messages(): array
    {
        $messages = [
            'id.exists'                              => 'El ticket no existe',
            'branch_id.required'                     => 'La sucursal es requerida',
            'branch_id.exists'                       => 'La sucursal no existe',
            'reservation_id.exists'                  => 'La reservación no existe',
            'cash_session_id.required'               => 'La sesión de caja es requerida',
            'cash_session_id.exists'                 => 'La sesión de caja no existe',
            'barber_id.exists'                       => 'El barbero no existe',
            'client_id.exists'                       => 'El cliente no existe',
            'services.*.service_branch_id.required'  => 'El servicio es requerido',
            'services.*.service_branch_id.exists'    => 'El servicio no existe',
            'services.*.quantity.min'                => 'La cantidad debe ser al menos 1',
            'services.*.amount.required'             => 'El monto del servicio es requerido',
            'services.*.amount.min'                  => 'El monto del servicio debe ser mayor o igual a 0',
            'services.*.discount.min'                => 'El descuento del servicio debe ser mayor o igual a 0',
            'products.*.presentation_id.required'    => 'La presentación es requerida',
            'products.*.presentation_id.exists'      => 'La presentación no existe',
            'products.*.quantity.required'            => 'La cantidad es requerida',
            'products.*.quantity.min'                 => 'La cantidad debe ser al menos 1',
            'products.*.unit_price.required'          => 'El precio unitario es requerido',
            'products.*.unit_price.min'               => 'El precio unitario debe ser mayor o igual a 0',
            'products.*.discount.min'                 => 'El descuento debe ser mayor o igual a 0',
        ];

        if ($this->has('income') && $this->income !== null) {
            $incomeRequest = new IncomeRequest();
            $incomeMessages = collect($incomeRequest->messages())
                ->mapWithKeys(fn($message, $key) => ["income.{$key}" => $message])
                ->all();
            $messages = array_merge($messages, $incomeMessages);
        }

        return $messages;
    }

    public function attributes(): array
    {
        $attributes = [
            'id'                           => 'ticket',
            'branch_id'                    => 'sucursal',
            'reservation_id'               => 'reservación',
            'cash_session_id'              => 'sesión de caja',
            'barber_id'                    => 'barbero',
            'client_id'                    => 'cliente',
            'services'                     => 'servicios',
            'services.*.service_branch_id' => 'servicio',
            'services.*.quantity'          => 'cantidad',
            'services.*.amount'            => 'monto',
            'services.*.discount'          => 'descuento',
            'products'                     => 'productos',
            'products.*.presentation_id'   => 'presentación',
            'products.*.quantity'          => 'cantidad',
            'products.*.unit_price'        => 'precio unitario',
            'products.*.discount'          => 'descuento',
        ];

        if ($this->has('income') && $this->income !== null) {
            $incomeRequest = new IncomeRequest();
            $incomeAttributes = collect($incomeRequest->attributes())
                ->mapWithKeys(fn($attribute, $key) => ["income.{$key}" => $attribute])
                ->all();
            $attributes = array_merge($attributes, $incomeAttributes);
        }

        return $attributes;
    }
}
