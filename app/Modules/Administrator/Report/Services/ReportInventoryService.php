<?php

namespace App\Modules\Administrator\Report\Services;

use App\Models\Reports\Report;
use App\Modules\Administrator\Report\Repositories\Actions\GenerateReportPdfAction;
use App\Modules\Administrator\Report\Repositories\Actions\SaveReportAction;
use App\Modules\Administrator\Report\Repositories\Queries\InventoryKardexQuery;
use App\Modules\Administrator\Report\Repositories\Queries\InventoryLowStockQuery;
use App\Modules\Administrator\Report\Repositories\Queries\InventorySalesPerDayQuery;
use App\Modules\Administrator\Report\Repositories\Queries\InventoryStockByInfrastructureQuery;
use App\Modules\Administrator\Report\Repositories\Queries\InventoryStockByProductQuery;
use App\Modules\Administrator\Report\Repositories\Queries\ReportDataTableQuery;
use App\Modules\Administrator\Report\Repositories\ReportInventoryRepository;
use Illuminate\Http\Request;

class ReportInventoryService
{
    public function __construct(
        private readonly ReportInventoryRepository $repository,
        private readonly InventoryKardexQuery $kardexQuery,
        private readonly InventoryStockByProductQuery $stockByProductQuery,
        private readonly InventoryStockByInfrastructureQuery $stockByInfrastructureQuery,
        private readonly InventorySalesPerDayQuery $salesPerDayQuery,
        private readonly InventoryLowStockQuery $lowStockQuery,
        private readonly GenerateReportPdfAction $generatePdfAction,
        private readonly SaveReportAction $saveReportAction,
        private readonly ReportDataTableQuery $reportDataTableQuery,
    ) {}

    // ─── Dashboard ───

    public function summary(Request $request): array
    {
        [$from, $to] = $this->getDateRange($request);
        $infrastructureId = $request->input('infrastructure_id') ? (int) $request->input('infrastructure_id') : null;

        return $this->repository->summary($from, $to, $infrastructureId);
    }

    public function salesTrend(Request $request): array
    {
        [$from, $to] = $this->getDateRange($request);
        $infrastructureId = $request->input('infrastructure_id') ? (int) $request->input('infrastructure_id') : null;

        return $this->repository->salesTrend($from, $to, $infrastructureId);
    }

    public function stockStatus(Request $request): array
    {
        $infrastructureId = $request->input('infrastructure_id') ? (int) $request->input('infrastructure_id') : null;

        return $this->repository->stockStatus($infrastructureId);
    }

    public function topProducts(Request $request): array
    {
        [$from, $to] = $this->getDateRange($request);
        $infrastructureId = $request->input('infrastructure_id') ? (int) $request->input('infrastructure_id') : null;

        return $this->repository->topProducts($from, $to, $infrastructureId);
    }

    public function purchaseOrderStatus(Request $request): array
    {
        $infrastructureId = $request->input('infrastructure_id') ? (int) $request->input('infrastructure_id') : null;

        return $this->repository->purchaseOrderStatus($infrastructureId);
    }

    public function productDetail(Request $request): array
    {
        $infrastructureId = $request->input('infrastructure_id') ? (int) $request->input('infrastructure_id') : null;

        return $this->repository->productDetail($infrastructureId);
    }

    // ─── DataTables ───

    public function dataTableKardex(Request $request): array
    {
        return ($this->reportDataTableQuery)($request, 'inventory_kardex');
    }

    public function dataTableStockByProduct(Request $request): array
    {
        return ($this->reportDataTableQuery)($request, 'inventory_stock_by_product');
    }

    public function dataTableStockByInfrastructure(Request $request): array
    {
        return ($this->reportDataTableQuery)($request, 'inventory_stock_by_infrastructure');
    }

    public function dataTableSalesPerDay(Request $request): array
    {
        return ($this->reportDataTableQuery)($request, 'inventory_sales_per_day');
    }

    public function dataTableLowStock(Request $request): array
    {
        return ($this->reportDataTableQuery)($request, 'inventory_low_stock');
    }

    // ─── PDF Generation ───

    public function generateKardexPdf(Request $request): Report
    {
        $presentationId = (int) $request->input('presentation_id');
        $infrastructureId = (int) $request->input('infrastructure_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $queryData = ($this->kardexQuery)($presentationId, $infrastructureId, $dateFrom, $dateTo);
        $bladeData = $this->kardexQuery->toBladeData($queryData);

        $filePath = $this->generatePdfAction->execute(
            bodyView: 'reports.inventory.kardex',
            headerView: 'reports.common.header',
            footerView: 'reports.common.footer',
            data: $bladeData,
            folder: 'inventory',
            filenameBase: "kardex-{$presentationId}-{$dateFrom}-{$dateTo}",
        );

        $productName = $queryData['presentation']->product->name;
        $infraName = $queryData['infrastructure']->name;
        $reference = "inventory_kardex,{$presentationId},{$infrastructureId},{$dateFrom},{$dateTo}";

        return $this->saveReportAction->execute(
            name: "Kardex - {$productName} - {$infraName} - {$dateFrom} a {$dateTo}",
            reference: $reference,
            type: 'inventory_kardex',
            data: $this->kardexQuery->toReportData($queryData),
            filePath: $filePath,
        );
    }

    public function generateStockByProductPdf(Request $request): Report
    {
        $infrastructureId = $request->input('infrastructure_id') ? (int) $request->input('infrastructure_id') : null;

        $queryData = ($this->stockByProductQuery)($infrastructureId);
        $bladeData = $this->stockByProductQuery->toBladeData($queryData);

        $filePath = $this->generatePdfAction->execute(
            bodyView: 'reports.inventory.stock-by-product',
            headerView: 'reports.common.header',
            footerView: 'reports.common.footer',
            data: $bladeData,
            folder: 'inventory',
            filenameBase: "stock-producto-" . now()->toDateString(),
        );

        $reference = "inventory_stock_by_product," . ($infrastructureId ?? 'all') . ',' . now()->toDateString();

        return $this->saveReportAction->execute(
            name: "Stock por Producto - " . ($queryData['infrastructure']?->name ?? 'Todas') . ' - ' . now()->toDateString(),
            reference: $reference,
            type: 'inventory_stock_by_product',
            data: $this->stockByProductQuery->toReportData($queryData),
            filePath: $filePath,
        );
    }

    public function generateStockByInfrastructurePdf(Request $request): Report
    {
        $infrastructureId = (int) $request->input('infrastructure_id');

        $queryData = ($this->stockByInfrastructureQuery)($infrastructureId);
        $bladeData = $this->stockByInfrastructureQuery->toBladeData($queryData);

        $filePath = $this->generatePdfAction->execute(
            bodyView: 'reports.inventory.stock-by-infrastructure',
            headerView: 'reports.common.header',
            footerView: 'reports.common.footer',
            data: $bladeData,
            folder: 'inventory',
            filenameBase: "stock-sede-{$infrastructureId}-" . now()->toDateString(),
        );

        $infraName = $queryData['infrastructure']->name;
        $reference = "inventory_stock_by_infrastructure,{$infrastructureId}," . now()->toDateString();

        return $this->saveReportAction->execute(
            name: "Stock por Sede - {$infraName} - " . now()->toDateString(),
            reference: $reference,
            type: 'inventory_stock_by_infrastructure',
            data: $this->stockByInfrastructureQuery->toReportData($queryData),
            filePath: $filePath,
        );
    }

    public function generateSalesPerDayPdf(Request $request): Report
    {
        $infrastructureId = (int) $request->input('infrastructure_id');
        $date = $request->input('date');

        $queryData = ($this->salesPerDayQuery)($infrastructureId, $date);
        $bladeData = $this->salesPerDayQuery->toBladeData($queryData);

        $filePath = $this->generatePdfAction->execute(
            bodyView: 'reports.inventory.sales-per-day',
            headerView: 'reports.common.header',
            footerView: 'reports.common.footer',
            data: $bladeData,
            folder: 'inventory',
            filenameBase: "ventas-diarias-{$date}",
        );

        $infraName = $queryData['infrastructure']->name;
        $reference = "inventory_sales_per_day,{$infrastructureId},{$date}";

        return $this->saveReportAction->execute(
            name: "Ventas del Día - {$infraName} - {$date}",
            reference: $reference,
            type: 'inventory_sales_per_day',
            data: $this->salesPerDayQuery->toReportData($queryData),
            filePath: $filePath,
        );
    }

    public function generateLowStockPdf(Request $request): Report
    {
        $infrastructureId = $request->input('infrastructure_id') ? (int) $request->input('infrastructure_id') : null;

        $queryData = ($this->lowStockQuery)($infrastructureId);
        $bladeData = $this->lowStockQuery->toBladeData($queryData);

        $filePath = $this->generatePdfAction->execute(
            bodyView: 'reports.inventory.low-stock',
            headerView: 'reports.common.header',
            footerView: 'reports.common.footer',
            data: $bladeData,
            folder: 'inventory',
            filenameBase: "stock-bajo-" . now()->toDateString(),
        );

        $reference = "inventory_low_stock," . ($infrastructureId ?? 'all') . ',' . now()->toDateString();

        return $this->saveReportAction->execute(
            name: "Stock Bajo - " . ($queryData['infrastructure']?->name ?? 'Todas') . ' - ' . now()->toDateString(),
            reference: $reference,
            type: 'inventory_low_stock',
            data: $this->lowStockQuery->toReportData($queryData),
            filePath: $filePath,
        );
    }

    // ─── Helpers ───

    private function getDateRange(Request $request): array
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to = $request->input('to', now()->toDateString()) . ' 23:59:59';

        return [$from, $to];
    }
}
