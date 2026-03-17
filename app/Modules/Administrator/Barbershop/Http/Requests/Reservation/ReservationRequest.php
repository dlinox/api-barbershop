<?php

namespace App\Modules\Administrator\Barbershop\Http\Requests\Reservation;

use App\Common\Http\Requests\ApiFormRequest;

class ReservationRequest extends ApiFormRequest
{
    public function rules()
    {
        $id = $this->id ?? null;
        return [
            'id' => $id ? 'exists:barbershop_reservations,id' : 'nullable',
            'branch_id' => 'required|exists:barbershop_branches,id',
            'profile_client_id' => 'required|exists:profile_clients,id',
            'service_id' => 'nullable|exists:barbershop_services,id',
            'date' => 'required|date',
            'time' => 'required',
            'status' => 'required|in:pending,confirmed,cancelled',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => 'La reservación no existe',
            'branch_id.required' => 'La sucursal es requerida',
            'branch_id.exists' => 'La sucursal no existe',
            'profile_client_id.required' => 'El cliente es requerido',
            'profile_client_id.exists' => 'El cliente no existe',
            'service_id.exists' => 'El servicio no existe',
            'date.required' => 'La fecha es requerida',
            'date.date' => 'La fecha debe ser válida',
            'time.required' => 'La hora es requerida',
            'status.required' => 'El estado es requerido',
            'status.in' => 'El estado debe ser pendiente, confirmado o cancelado',
        ];
    }

    public function attributes(): array
    {
        return [
            'id' => 'ID',
            'branch_id' => 'Sucursal',
            'profile_client_id' => 'Cliente',
            'service_id' => 'Servicio',
            'date' => 'Fecha',
            'time' => 'Hora',
            'status' => 'Estado',
        ];
    }
}
