<?php

namespace App\Modules\AcademyPanel\Report\Http\Controllers;

use App\Common\Helpers\FileHelper;
use App\Common\Http\Context\AdminContext;
use App\Common\Http\Responses\ApiResponse;
use App\Models\Reports\Report;
use App\Modules\AcademyPanel\Report\Http\Requests\GenerateAttendanceByGroupRequest;
use App\Modules\AcademyPanel\Report\Http\Requests\GenerateIncomePerDayRequest;
use App\Modules\AcademyPanel\Report\Http\Requests\GenerateStudentListByGroupRequest;
use App\Modules\AcademyPanel\Report\Services\AcademyPanelReportService;
use App\Modules\Administrator\Report\Http\Resources\ReportDataTableItemResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcademyPanelReportController
{
    public function __construct(
        private readonly AcademyPanelReportService $service,
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

    public function generateIncomePerDayPdf(GenerateIncomePerDayRequest $request): JsonResponse
    {
        $branchId = AdminContext::academyBranchId();
        $report = $this->service->generateIncomePerDayPdf($request, $branchId);
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
