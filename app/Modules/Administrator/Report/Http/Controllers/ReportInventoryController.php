<?php

namespace App\Modules\Administrator\Report\Http\Controllers;

use App\Common\Helpers\FileHelper;
use App\Common\Http\Responses\ApiResponse;
use App\Models\Reports\Report;
use App\Modules\Administrator\Report\Http\Requests\GenerateInventoryKardexRequest;
use App\Modules\Administrator\Report\Http\Requests\GenerateInventoryLowStockRequest;
use App\Modules\Administrator\Report\Http\Requests\GenerateInventorySalesPerDayRequest;
use App\Modules\Administrator\Report\Http\Requests\GenerateInventoryStockByInfrastructureRequest;
use App\Modules\Administrator\Report\Http\Requests\GenerateInventoryStockByProductRequest;
use App\Modules\Administrator\Report\Http\Resources\ReportDataTableItemResource;
use App\Modules\Administrator\Report\Services\ReportInventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportInventoryController
{
    public function __construct(
        private readonly ReportInventoryService $service,
    ) {}

    // ─── Dashboard ───

    public function summary(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->summary($request));
    }

    public function salesTrend(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->salesTrend($request));
    }

    public function stockStatus(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->stockStatus($request));
    }

    public function topProducts(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->topProducts($request));
    }

    public function purchaseOrderStatus(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->purchaseOrderStatus($request));
    }

    public function productDetail(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->productDetail($request));
    }

    // ─── DataTables ───

    public function dataTableKardex(Request $request): JsonResponse
    {
        $items = $this->service->dataTableKardex($request);
        $items['data'] = ReportDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function dataTableStockByProduct(Request $request): JsonResponse
    {
        $items = $this->service->dataTableStockByProduct($request);
        $items['data'] = ReportDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function dataTableStockByInfrastructure(Request $request): JsonResponse
    {
        $items = $this->service->dataTableStockByInfrastructure($request);
        $items['data'] = ReportDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function dataTableSalesPerDay(Request $request): JsonResponse
    {
        $items = $this->service->dataTableSalesPerDay($request);
        $items['data'] = ReportDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function dataTableLowStock(Request $request): JsonResponse
    {
        $items = $this->service->dataTableLowStock($request);
        $items['data'] = ReportDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    // ─── Generación de PDFs ───

    public function generateKardexPdf(GenerateInventoryKardexRequest $request): JsonResponse
    {
        $report = $this->service->generateKardexPdf($request);
        return ApiResponse::success(['reportId' => $report->id]);
    }

    public function generateStockByProductPdf(GenerateInventoryStockByProductRequest $request): JsonResponse
    {
        $report = $this->service->generateStockByProductPdf($request);
        return ApiResponse::success(['reportId' => $report->id]);
    }

    public function generateStockByInfrastructurePdf(GenerateInventoryStockByInfrastructureRequest $request): JsonResponse
    {
        $report = $this->service->generateStockByInfrastructurePdf($request);
        return ApiResponse::success(['reportId' => $report->id]);
    }

    public function generateSalesPerDayPdf(GenerateInventorySalesPerDayRequest $request): JsonResponse
    {
        $report = $this->service->generateSalesPerDayPdf($request);
        return ApiResponse::success(['reportId' => $report->id]);
    }

    public function generateLowStockPdf(GenerateInventoryLowStockRequest $request): JsonResponse
    {
        $report = $this->service->generateLowStockPdf($request);
        return ApiResponse::success(['reportId' => $report->id]);
    }

    public function viewPdf(int $id)
    {
        $report = Report::findOrFail($id);

        $content = file_get_contents(FileHelper::getFilePath('reports', $report->file_path));

        return response($content, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($report->file_path) . '"',
        ]);
    }
}
