<?php

namespace App\Modules\AcademyPanel\Dashboard\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'activeGroupsCount'      => (int)   $this->resource['active_groups_count'],
            'enrolledStudentsCount'  => (int)   $this->resource['enrolled_students_count'],
            'attendanceToday'        => (int)   $this->resource['attendance_today'],
            'incomeMonth'            => (float) $this->resource['income_month'],
            'incomeMonthCount'       => (int)   $this->resource['income_month_count'],
            'cashMonth'              => (float) $this->resource['cash_month'],
            'bankMonth'              => (float) $this->resource['bank_month'],
            'enrollmentsThisMonth'   => (int)   $this->resource['enrollments_this_month'],
        ];
    }
}