<?php

namespace App\Modules\Shared\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Modules\Shared\Http\Resources\PersonItemResource;
use App\Modules\Shared\Services\PersonService;
use Illuminate\Http\Request;

class PersonController
{
    public function __construct(
        private PersonService $personService
    ) {}

    public function selectAsyncItems(Request $request)
    {
        $items = $this->personService->selectAsyncItems($request);
        $items = PersonItemResource::collection($items);
        return ApiResponse::success($items);
    }
}
