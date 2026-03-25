<?php

namespace App\Modules\Administrator\Report\Http\Controllers;

use App\Common\Helpers\FileHelper;
use App\Common\Http\Responses\ApiResponse;
use App\Models\Reports\Report;
use App\Modules\Administrator\Report\Http\Requests\GenerateBarberCommissionsRequest;
use App\Modules\Administrator\Report\Http\Requests\GenerateBarbershopIncomePerDayRequest;
use App\Modules\Administrator\Report\Http\Requests\GenerateCashSessionSummaryRequest;
use App\Modules\Administrator\Report\Http\Resources\ReportDataTableItemResource;
use App\Modules\Administrator\Report\Services\ReportBarbershopService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportBarbershopController
{
    public function __construct(
        private readonly ReportBarbershopService $service,
    ) {}

    // ─── Dashboard ───

    public function summary(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->summary($request));
    }

    public function revenueTrend(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->revenueTrend($request));
    }

    public function topBarbers(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->topBarbers($request));
    }

    public function servicesBreakdown(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->servicesBreakdown($request));
    }

    public function ticketsByStatus(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->ticketsByStatus($request));
    }

    public function hourlyDistribution(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->hourlyDistribution($request));
    }

    public function serviceDetail(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->serviceDetail($request));
    }

    // ─── DataTables ───

    public function dataTableIncomePerDay(Request $request): JsonResponse
    {
        $items = $this->service->dataTableIncomePerDay($request);
        $items['data'] = ReportDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function dataTableBarberCommissions(Request $request): JsonResponse
    {
        $items = $this->service->dataTableBarberCommissions($request);
        $items['data'] = ReportDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function dataTableCashSessionSummary(Request $request): JsonResponse
    {
        $items = $this->service->dataTableCashSessionSummary($request);
        $items['data'] = ReportDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    // ─── Generación de PDFs ───

    public function generateIncomePerDayPdf(GenerateBarbershopIncomePerDayRequest $request): JsonResponse
    {
        $report = $this->service->generateIncomePerDayPdf($request);
        return ApiResponse::success(['reportId' => $report->id]);
    }

    public function generateBarberCommissionsPdf(GenerateBarberCommissionsRequest $request): JsonResponse
    {
        $report = $this->service->generateBarberCommissionsPdf($request);
        return ApiResponse::success(['reportId' => $report->id]);
    }

    public function generateCashSessionSummaryPdf(GenerateCashSessionSummaryRequest $request): JsonResponse
    {
        $report = $this->service->generateCashSessionSummaryPdf($request);
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
