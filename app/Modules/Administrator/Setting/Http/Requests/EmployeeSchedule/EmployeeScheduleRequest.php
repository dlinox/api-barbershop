<?php

namespace App\Modules\Administrator\Setting\Http\Requests\EmployeeSchedule;

use App\Common\Http\Requests\ApiFormRequest;

class EmployeeScheduleRequest extends ApiFormRequest
{
    public function rules()
    {
        return [
            'schedules' => 'required|array|size:2',
            'schedules.*.type' => 'required|in:barber,worker',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.is_active' => 'boolean',
        ];
    }
}
