<?php

namespace App\Modules\Administrator\Academy\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Academy\Http\Requests\Enrollment\ChangeGroupRequest;
use App\Modules\Administrator\Academy\Services\ChangeGroupService;

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
