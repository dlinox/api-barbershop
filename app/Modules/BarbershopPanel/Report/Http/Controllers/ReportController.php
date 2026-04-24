<?php

namespace App\Modules\BarbershopPanel\Report\Http\Controllers;

use App\Common\Helpers\FileHelper;
use App\Common\Http\Responses\ApiResponse;
use App\Models\Reports\Report;
use App\Modules\BarbershopPanel\Report\Http\Requests\GenerateBarberCommissionsRequest;
use App\Modules\BarbershopPanel\Report\Http\Requests\GenerateCashSessionSummaryRequest;
use App\Modules\BarbershopPanel\Report\Http\Requests\GenerateIncomePerDayRequest;
use App\Modules\BarbershopPanel\Report\Http\Resources\ReportDataTableItemResource;
use App\Modules\BarbershopPanel\Report\Services\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController
{
    public function __construct(
        private readonly ReportService $service,
    ) {}

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

    public function selectCashSessions(): JsonResponse
    {
        return ApiResponse::success($this->service->selectCashSessions());
    }

    public function generateIncomePerDayPdf(GenerateIncomePerDayRequest $request): JsonResponse
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
