<?php

namespace App\Modules\Administrator\Academy\Services;

use Illuminate\Http\Request;
use App\Modules\Administrator\Academy\Repositories\TeacherAttendanceRepository;

class TeacherAttendanceService
{
    public function __construct(
        private TeacherAttendanceRepository $teacherAttendanceRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->teacherAttendanceRepository->dataTable($request);
    }
}
