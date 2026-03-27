<?php

namespace App\Modules\StudentPanel\Enrollment\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\StudentPanel\Enrollment\Http\Resources\EnrollmentResource;
use App\Modules\StudentPanel\Enrollment\Services\EnrollmentService;
use Illuminate\Http\JsonResponse;

class EnrollmentController
{
    public function __construct(
        private readonly EnrollmentService $service,
    ) {}

    public function list(): JsonResponse
    {
        return ApiResponse::success(EnrollmentResource::collection($this->service->list()));
    }
}
