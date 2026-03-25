<?php

namespace App\Modules\Administrator\Report\Http\Controllers;

use App\Common\Helpers\FileHelper;
use App\Common\Http\Responses\ApiResponse;
use App\Models\Reports\Report;
use App\Modules\Administrator\Report\Http\Requests\GenerateAttendanceByGroupRequest;
use App\Modules\Administrator\Report\Http\Requests\GenerateIncomePerDayRequest;
use App\Modules\Administrator\Report\Http\Requests\GenerateStudentListByGroupRequest;
use App\Modules\Administrator\Report\Http\Resources\ReportDataTableItemResource;
use App\Modules\Administrator\Report\Services\ReportAcademyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportAcademyController
{
    public function __construct(
        private readonly ReportAcademyService $service,
    ) {}

    public function dataTable(Request $request): JsonResponse
    {
        $items = $this->service->dataTable($request);
        $items['data'] = ReportDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function dataTableAttendanceByGroup(Request $request): JsonResponse
    {
        $items = $this->service->dataTableAttendanceByGroup($request);
        $items['data'] = ReportDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function dataTableStudentListByGroup(Request $request): JsonResponse
    {
        $items = $this->service->dataTableStudentListByGroup($request);
        $items['data'] = ReportDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function summary(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->summary($request));
    }

    public function enrollmentTrend(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->enrollmentTrend($request));
    }

    public function revenueByType(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->revenueByType($request));
    }

    public function groupOccupancy(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->groupOccupancy($request));
    }

    public function groupDetail(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->groupDetail($request));
    }

    public function dailyIncome(Request $request): JsonResponse
    {
        return ApiResponse::success($this->service->dailyIncome($request));
    }

    public function generateIncomePerDayPdf(GenerateIncomePerDayRequest $request): JsonResponse
    {
        $report = $this->service->generateIncomePerDayPdf($request);

        return ApiResponse::success(['reportId' => $report->id]);
    }

    public function generateAttendanceByGroupPdf(GenerateAttendanceByGroupRequest $request): JsonResponse
    {
        $report = $this->service->generateAttendanceByGroupPdf($request);

        return ApiResponse::success(['reportId' => $report->id]);
    }

    public function generateStudentListByGroupPdf(GenerateStudentListByGroupRequest $request): JsonResponse
    {
        $report = $this->service->generateStudentListByGroupPdf($request);

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
