<?php

namespace App\Modules\Administrator\Report\Http\Controllers;

use App\Common\Helpers\FileHelper;
use App\Common\Http\Responses\ApiResponse;
use App\Models\Reports\Report;
use App\Modules\Administrator\Report\Http\Requests\GenerateTreasuryCashSessionRequest;
use App\Modules\Administrator\Report\Http\Requests\GenerateTreasuryExpensePerDayRequest;
use App\Modules\Administrator\Report\Http\Requests\GenerateTreasuryIncomePerDayRequest;
use App\Modules\Administrator\Report\Http\Requests\GenerateTreasuryIncomeVsExpenseRequest;
use App\Modules\Administrator\Report\Http\Requests\GenerateTreasuryPendingExpensesRequest;
use App\Modules\Administrator\Report\Http\Resources\ReportDataTableItemResource;
use App\Modules\Administrator\Report\Services\ReportTreasuryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportTreasuryController
{
    public function __construct(
        private readonly ReportTreasuryService $service,
    ) {}

    // ─── Dashboard ───

    public function summary(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->summary($request));
    }

    public function incomeVsExpenses(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->incomeVsExpenses($request));
    }

    public function paymentMethodsDistribution(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->paymentMethodsDistribution($request));
    }

    public function expensesByType(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->expensesByType($request));
    }

    public function cashSessionDetail(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->cashSessionDetail($request));
    }

    // ─── DataTables ───

    public function dataTableIncomePerDay(Request $request): JsonResponse
    {
        $items = $this->service->dataTableIncomePerDay($request);
        $items['data'] = ReportDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function dataTableExpensePerDay(Request $request): JsonResponse
    {
        $items = $this->service->dataTableExpensePerDay($request);
        $items['data'] = ReportDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function dataTableCashSession(Request $request): JsonResponse
    {
        $items = $this->service->dataTableCashSession($request);
        $items['data'] = ReportDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function selectCashSessions(): JsonResponse
    {
        return ApiResponse::success($this->service->selectCashSessions());
    }

    public function dataTableIncomeVsExpense(Request $request): JsonResponse
    {
        $items = $this->service->dataTableIncomeVsExpense($request);
        $items['data'] = ReportDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function dataTablePendingExpenses(Request $request): JsonResponse
    {
        $items = $this->service->dataTablePendingExpenses($request);
        $items['data'] = ReportDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    // ─── Generación de PDFs ───

    public function generateIncomePerDayPdf(GenerateTreasuryIncomePerDayRequest $request): JsonResponse
    {
        $report = $this->service->generateIncomePerDayPdf($request);
        return ApiResponse::success(['reportId' => $report->id]);
    }

    public function generateExpensePerDayPdf(GenerateTreasuryExpensePerDayRequest $request): JsonResponse
    {
        $report = $this->service->generateExpensePerDayPdf($request);
        return ApiResponse::success(['reportId' => $report->id]);
    }

    public function generateCashSessionPdf(GenerateTreasuryCashSessionRequest $request): JsonResponse
    {
        $report = $this->service->generateCashSessionPdf($request);
        return ApiResponse::success(['reportId' => $report->id]);
    }

    public function generateIncomeVsExpensePdf(GenerateTreasuryIncomeVsExpenseRequest $request): JsonResponse
    {
        $report = $this->service->generateIncomeVsExpensePdf($request);
        return ApiResponse::success(['reportId' => $report->id]);
    }

    public function generatePendingExpensesPdf(GenerateTreasuryPendingExpensesRequest $request): JsonResponse
    {
        $report = $this->service->generatePendingExpensesPdf($request);
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
