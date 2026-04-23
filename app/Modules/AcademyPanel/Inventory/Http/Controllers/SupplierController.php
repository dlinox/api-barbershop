<?php

namespace App\Modules\AcademyPanel\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Inventory\Services\SupplierService;
use App\Modules\Administrator\Inventory\Http\Requests\Supplier\SupplierRequest;
use App\Modules\Administrator\Inventory\Http\Resources\Supplier\SupplierDataTableItemResource;
use App\Modules\Administrator\Inventory\Http\Resources\Supplier\SupplierSelectItemResource;

class SupplierController
{
    public function __construct(
        private SupplierService $supplierService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->supplierService->dataTable($request);
        $items['data'] = SupplierDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(SupplierRequest $request)
    {
        $this->supplierService->save($request->validated());
        return ApiResponse::success(null, 'Proveedor guardado correctamente');
    }

    public function delete(int $id)
    {
        $this->supplierService->delete($id);
        return ApiResponse::success(null, 'Proveedor eliminado correctamente');
    }

    public function selectItems()
    {
        $items = $this->supplierService->getActiveSuppliers();
        return ApiResponse::success(SupplierSelectItemResource::collection($items));
    }
}
