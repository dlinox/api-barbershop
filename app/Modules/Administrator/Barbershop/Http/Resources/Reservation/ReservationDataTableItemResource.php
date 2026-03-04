<?php

namespace App\Modules\Administrator\Barbershop\Http\Resources\Reservation;

use Illuminate\Http\Resources\Json\JsonResource;

class ReservationDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'branchId' => $this->branch_id,
            'branchName' => $this->branch_name,
            'profileClientId' => $this->profile_client_id,
            'clientName' => trim($this->client_name . ' ' . $this->client_paternal_surname . ' ' . $this->client_maternal_surname),
            'serviceBranchId' => $this->service_branch_id,
            'serviceName' => $this->service_name,
            'date' => $this->date,
            'time' => $this->time,
            'status' => $this->status,
        ];
    }
}
