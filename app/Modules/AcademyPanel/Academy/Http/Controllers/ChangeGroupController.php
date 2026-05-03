<?php

namespace App\Modules\AcademyPanel\Academy\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\AcademyPanel\Academy\Http\Requests\Enrollment\ChangeGroupRequest;
use App\Modules\AcademyPanel\Academy\Services\ChangeGroupService;

class ChangeGroupController
{
    public function __construct(
        private ChangeGroupService $changeGroupService,
    ) {}

    public function changeGroup(ChangeGroupRequest $request)
    {
        $result = $this->changeGroupService->changeGroup($request->validated());
        return ApiResponse::success($result, 'Cambio de grupo realizado correctamente.');
    }
}
