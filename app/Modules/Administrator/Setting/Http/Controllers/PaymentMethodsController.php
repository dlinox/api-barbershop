<?php

namespace App\Modules\Administrator\Setting\Http\Controllers;

use Illuminate\Http\Request;

use App\Common\Http\Responses\ApiResponse;

use App\Modules\Administrator\Setting\Services\PaymentMethodsService;

use App\Modules\Administrator\Setting\Http\Requests\PaymentMethods\PaymentMethodsRequest;
use App\Modules\Administrator\Setting\Http\Resources\PaymentMethods\PaymentMethodsDataTableItemResource;
use App\Modules\Administrator\Setting\Http\Resources\PaymentMethods\PaymentMethodsSelectItemResource;

class PaymentMethodsController
{

    public function __construct(
        private PaymentMethodsService $paymentMethodsService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->paymentMethodsService->dataTable($request);

        $items['data'] = PaymentMethodsDataTableItemResource::collection($items['data']);

        return ApiResponse::success($items);
    }

    public function save(PaymentMethodsRequest $request)
    {
        $data = $request->validated();
        $this->paymentMethodsService->save($data);
        return ApiResponse::success($data, 'Método de pago guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->paymentMethodsService->delete($id);
        return ApiResponse::success(null, 'Método de pago eliminado correctamente');
    }

    public function getActivePaymentMethods()
    {
        $items = $this->paymentMethodsService->getActivePaymentMethods();
        $items = PaymentMethodsDataTableItemResource::collection($items);
        return ApiResponse::success($items);
    }
}
