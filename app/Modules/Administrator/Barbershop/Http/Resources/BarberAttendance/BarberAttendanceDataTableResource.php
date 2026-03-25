<?php

namespace App\Modules\Administrator\Barbershop\Http\Resources\BarberAttendance;

use Illuminate\Http\Resources\Json\JsonResource;

class BarberAttendanceDataTableResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'barber' => [
                'id' => $this->barber_id,
                'personId' => $this->barber_person_id,
                'name' => $this->barber_name,
                'paternalSurname' => $this->barber_paternal_surname,
                'maternalSurname' => $this->barber_maternal_surname,
                'document' => $this->barber_document,
                'branchId' => $this->barber_branch_id,
                'branchName' => $this->barber_branch_name,
            ],
            'attendance' => [
                'id' => $this->attendance_id,
                'date' => $this->attendance_date,
                'checkIn' => $this->check_in,
                'checkOut' => $this->check_out,
                'checkToken' => $this->check_token,
                'checkType' => $this->check_type,
                'status' => $this->attendance_status,
                'observation' => $this->observation,
            ],
        ];
    }
}
