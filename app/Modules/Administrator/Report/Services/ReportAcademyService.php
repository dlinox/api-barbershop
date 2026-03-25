<?php

namespace App\Modules\Administrator\Report\Services;

use App\Models\Reports\Report;
use App\Modules\Administrator\Report\Repositories\Actions\GenerateReportPdfAction;
use App\Modules\Administrator\Report\Repositories\Actions\SaveReportAction;
use App\Modules\Administrator\Report\Repositories\Queries\AcademyAttendanceByGroupQuery;
use App\Modules\Administrator\Report\Repositories\Queries\AcademyIncomePerDayQuery;
use App\Modules\Administrator\Report\Repositories\Queries\AcademyStudentListByGroupQuery;
use App\Modules\Administrator\Report\Repositories\Queries\ReportDataTableQuery;
use App\Modules\Administrator\Report\Repositories\ReportAcademyRepository;
use Illuminate\Http\Request;

class ReportAcademyService
{
    public function __construct(
        private readonly ReportAcademyRepository $repository,
        private readonly AcademyIncomePerDayQuery $incomePerDayQuery,
        private readonly AcademyAttendanceByGroupQuery $attendanceByGroupQuery,
        private readonly AcademyStudentListByGroupQuery $studentListByGroupQuery,
        private readonly GenerateReportPdfAction $generatePdfAction,
        private readonly SaveReportAction $saveReportAction,
        private readonly ReportDataTableQuery $reportDataTableQuery,
    ) {}

    public function summary(Request $request): array
    {
        [$from, $to] = $this->getDateRange($request);
        $branchId = $request->input('branch_id') ? (int) $request->input('branch_id') : null;

        return $this->repository->summary($from, $to, $branchId);
    }

    public function enrollmentTrend(Request $request): array
    {
        [$from, $to] = $this->getDateRange($request);
        $branchId = $request->input('branch_id') ? (int) $request->input('branch_id') : null;

        return $this->repository->enrollmentTrend($from, $to, $branchId);
    }

    public function revenueByType(Request $request): array
    {
        [$from, $to] = $this->getDateRange($request);
        $branchId = $request->input('branch_id') ? (int) $request->input('branch_id') : null;

        return $this->repository->revenueByType($from, $to, $branchId);
    }

    public function groupOccupancy(Request $request): array
    {
        $branchId = $request->input('branch_id') ? (int) $request->input('branch_id') : null;

        return $this->repository->groupOccupancy($branchId);
    }

    public function groupDetail(Request $request): array
    {
        [$from, $to] = $this->getDateRange($request);
        $branchId = $request->input('branch_id') ? (int) $request->input('branch_id') : null;

        return $this->repository->groupDetail($from, $to, $branchId);
    }

    public function dailyIncome(Request $request): array
    {
        $date = $request->input('date', now()->toDateString());
        $branchId = $request->input('branch_id') ? (int) $request->input('branch_id') : null;

        return $this->repository->dailyIncome($date, $branchId);
    }

    // ─── DataTables ───

    public function dataTable(Request $request): array
    {
        return ($this->reportDataTableQuery)($request, 'academy_income_per_day');
    }

    public function dataTableAttendanceByGroup(Request $request): array
    {
        return ($this->reportDataTableQuery)($request, 'academy_attendance_by_group');
    }

    public function dataTableStudentListByGroup(Request $request): array
    {
        return ($this->reportDataTableQuery)($request, 'academy_student_list_by_group');
    }

    // ─── Generación de PDFs ───

    public function generateIncomePerDayPdf(Request $request): Report
    {
        $branchId = (int) $request->input('branch_id');
        $date = $request->input('date');
        $workerId = (int) $request->input('worker_id');

        $queryData = ($this->incomePerDayQuery)($branchId, $date, $workerId);
        $bladeData = $this->incomePerDayQuery->toBladeData($queryData);

        $filePath = $this->generatePdfAction->execute(
            bodyView: 'reports.academy.income-per-day',
            headerView: 'reports.common.header',
            footerView: 'reports.common.footer',
            data: $bladeData,
            folder: 'academy',
            filenameBase: "ingreso-diario-academia-{$date}",
        );

        $branchName = $queryData['branch']->name;
        $reference = "academy_income_per_day,{$branchId},{$date}";

        return $this->saveReportAction->execute(
            name: "Ingreso Diario Academia - {$branchName} - {$date}",
            reference: $reference,
            type: 'academy_income_per_day',
            data: $this->incomePerDayQuery->toReportData($queryData),
            filePath: $filePath,
        );
    }

    public function generateAttendanceByGroupPdf(Request $request): Report
    {
        $groupId = (int) $request->input('group_id');
        $month = (int) $request->input('month');
        $year = (int) $request->input('year');

        $queryData = ($this->attendanceByGroupQuery)($groupId, $month, $year);
        $bladeData = $this->attendanceByGroupQuery->toBladeData($queryData);

        $filePath = $this->generatePdfAction->execute(
            bodyView: 'reports.academy.attendance-by-group',
            headerView: 'reports.common.header',
            footerView: 'reports.common.footer',
            data: $bladeData,
            folder: 'academy',
            filenameBase: "asistencia-grupo-{$groupId}-{$year}-{$month}",
            pdfConfig: ['orientation' => 'L'],
        );

        $groupName = $queryData['group']->name;
        $branchName = $queryData['group']->branch->name;
        $monthNames = [1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',5=>'Mayo',6=>'Junio',
                       7=>'Julio',8=>'Agosto',9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'];
        $reference = "academy_attendance_by_group,{$groupId},{$year},{$month}";

        return $this->saveReportAction->execute(
            name: "Asistencia {$groupName} - {$branchName} - {$monthNames[$month]} {$year}",
            reference: $reference,
            type: 'academy_attendance_by_group',
            data: $this->attendanceByGroupQuery->toReportData($queryData),
            filePath: $filePath,
        );
    }

    public function generateStudentListByGroupPdf(Request $request): Report
    {
        $groupId = (int) $request->input('group_id');

        $queryData = ($this->studentListByGroupQuery)($groupId);
        $bladeData = $this->studentListByGroupQuery->toBladeData($queryData);

        $filePath = $this->generatePdfAction->execute(
            bodyView: 'reports.academy.student-list-by-group',
            headerView: 'reports.common.header',
            footerView: 'reports.common.footer',
            data: $bladeData,
            folder: 'academy',
            filenameBase: "lista-alumnos-grupo-{$groupId}",
        );

        $groupName = $queryData['group']->name;
        $branchName = $queryData['group']->branch->name;
        $reference = "academy_student_list_by_group,{$groupId}";

        return $this->saveReportAction->execute(
            name: "Lista Alumnos {$groupName} - {$branchName}",
            reference: $reference,
            type: 'academy_student_list_by_group',
            data: $this->studentListByGroupQuery->toReportData($queryData),
            filePath: $filePath,
        );
    }

    private function getDateRange(Request $request): array
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to = $request->input('to', now()->toDateString()) . ' 23:59:59';

        return [$from, $to];
    }
}
