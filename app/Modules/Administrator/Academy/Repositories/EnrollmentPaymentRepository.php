<?php

namespace App\Modules\Administrator\Academy\Repositories;

use App\Models\Academy\Enrollment;

class EnrollmentPaymentRepository
{
    public function dataTable($request)
    {
        return Enrollment::dataTable($request);
    }

    public function save($data)
    {
        return Enrollment::create($data);
    }
}
