<?php

namespace App\Modules\Administrator\Treasury\Http\Resources\WorkerAttendance;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkerAttendanceDataTableResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'worker' => [
                'id' => $this->worker_id,
                'personId' => $this->worker_person_id,
                'name' => $this->worker_name,
                'paternalSurname' => $this->worker_paternal_surname,
                'maternalSurname' => $this->worker_maternal_surname,
                'document' => $this->worker_document,
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
