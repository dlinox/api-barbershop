<?php

namespace App\Modules\BarbershopPanel\Barbershop\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Modules\BarbershopPanel\Barbershop\Services\ClientService;
use App\Modules\Administrator\Barbershop\Http\Requests\Client\ClientRequest;
use App\Modules\Administrator\Barbershop\Http\Resources\Client\ClientDataTableItemResource;
use App\Modules\Administrator\Barbershop\Http\Resources\Client\ClientSelectItemResource;

class ClientController
{
    public function __construct(private ClientService $clientService) {}

    public function dataTable(Request $request)
    {
        $item = $this->clientService->dataTable($request);
        $item['data'] = ClientDataTableItemResource::collection($item['data']);
        return ApiResponse::success($item);
    }

    public function save(ClientRequest $request)
    {
        $this->clientService->save($request->validated());
        return ApiResponse::success(null, 'Cliente guardado correctamente');
    }

    public function selectAsyncItems(Request $request)
    {
        $item = ClientSelectItemResource::collection($this->clientService->selectAsyncItems($request));
        return ApiResponse::success($item);
    }
}