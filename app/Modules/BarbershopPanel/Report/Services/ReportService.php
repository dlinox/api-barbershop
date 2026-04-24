<?php

namespace App\Modules\BarbershopPanel\Report\Services;

use App\Common\Http\Context\AdminContext;
use App\Models\Reports\Report;
use App\Modules\Administrator\Report\Repositories\Actions\GenerateReportPdfAction;
use App\Modules\Administrator\Report\Repositories\Actions\SaveReportAction;
use App\Modules\Administrator\Report\Repositories\Queries\BarberCommissionsQuery;
use App\Modules\Administrator\Report\Repositories\Queries\BarbershopIncomePerDayQuery;
use App\Modules\Administrator\Report\Repositories\Queries\CashSessionSummaryQuery;
use App\Modules\Administrator\Report\Repositories\Queries\ReportDataTableQuery;
use App\Modules\BarbershopPanel\Report\Repositories\ReportRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportService
{
    public function __construct(
        private readonly ReportRepository $repository,
        private readonly BarbershopIncomePerDayQuery $incomePerDayQuery,
        private readonly BarberCommissionsQuery $barberCommissionsQuery,
        private readonly CashSessionSummaryQuery $cashSessionSummaryQuery,
        private readonly GenerateReportPdfAction $generatePdfAction,
        private readonly SaveReportAction $saveReportAction,
        private readonly ReportDataTableQuery $reportDataTableQuery,
    ) {}

    // ─── DataTables ───

    public function dataTableIncomePerDay(Request $request): array
    {
        $branchId = AdminContext::barbershopBranchId();

        $request->merge(['filters' => array_merge($request->input('filters', []), ['branch_id' => $branchId])]);

        return ($this->reportDataTableQuery)($request, 'barbershop_income_per_day');
    }

    public function dataTableBarberCommissions(Request $request): array
    {
        $branchId = AdminContext::barbershopBranchId();

        $request->merge(['filters' => array_merge($request->input('filters', []), ['branch_id' => $branchId])]);

        return ($this->reportDataTableQuery)($request, 'barbershop_barber_commissions');
    }

    public function dataTableCashSessionSummary(Request $request): array
    {
        return ($this->reportDataTableQuery)($request, 'barbershop_cash_session_summary');
    }

    public function selectCashSessions(): array
    {
        return $this->repository->selectCashSessions()->toArray();
    }

    // ─── Generación de PDFs ───

    public function generateIncomePerDayPdf(Request $request): Report
    {
        $branchId = AdminContext::barbershopBranchId();
        $date = $request->input('date');

        $queryData = ($this->incomePerDayQuery)($branchId, $date);
        $bladeData = $this->incomePerDayQuery->toBladeData($queryData);

        $filePath = $this->generatePdfAction->execute(
            bodyView: 'reports.barbershop.income-per-day',
            headerView: 'reports.common.header',
            footerView: 'reports.common.footer',
            data: $bladeData,
            folder: 'barbershop',
            filenameBase: "ingreso-diario-barberia-{$date}",
        );

        $branchName = $queryData['branch']->name;
        $reference = "barbershop_income_per_day,{$branchId},{$date}";

        return $this->saveReportAction->execute(
            name: "Ingreso Diario Barbería - {$branchName} - {$date}",
            reference: $reference,
            type: 'barbershop_income_per_day',
            data: $this->incomePerDayQuery->toReportData($queryData),
            filePath: $filePath,
        );
    }

    public function generateBarberCommissionsPdf(Request $request): Report
    {
        $branchId = AdminContext::barbershopBranchId();
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $queryData = ($this->barberCommissionsQuery)($branchId, $dateFrom, $dateTo);
        $bladeData = $this->barberCommissionsQuery->toBladeData($queryData);

        $filePath = $this->generatePdfAction->execute(
            bodyView: 'reports.barbershop.barber-commissions',
            headerView: 'reports.common.header',
            footerView: 'reports.common.footer',
            data: $bladeData,
            folder: 'barbershop',
            filenameBase: "comisiones-barberos-{$dateFrom}-{$dateTo}",
        );

        $branchName = $queryData['branch']->name;
        $reference = "barbershop_barber_commissions,{$branchId},{$dateFrom},{$dateTo}";

        return $this->saveReportAction->execute(
            name: "Comisiones Barberos - {$branchName} - {$dateFrom} a {$dateTo}",
            reference: $reference,
            type: 'barbershop_barber_commissions',
            data: $this->barberCommissionsQuery->toReportData($queryData),
            filePath: $filePath,
        );
    }

    public function generateCashSessionSummaryPdf(Request $request): Report
    {
        $cashSessionId = (int) $request->input('cash_session_id');

        $queryData = ($this->cashSessionSummaryQuery)($cashSessionId);
        $bladeData = $this->cashSessionSummaryQuery->toBladeData($queryData);

        $session = $queryData['session'];
        $date = $session->opened_at ? Carbon::parse($session->opened_at)->toDateString() : now()->toDateString();

        $filePath = $this->generatePdfAction->execute(
            bodyView: 'reports.barbershop.cash-session-summary',
            headerView: 'reports.common.header',
            footerView: 'reports.common.footer',
            data: $bladeData,
            folder: 'barbershop',
            filenameBase: "cierre-caja-{$cashSessionId}-{$date}",
        );

        $infraName = $queryData['infrastructure_name'];
        $registerName = $session->cashRegister?->name ?? 'Caja';
        $reference = "barbershop_cash_session_summary,{$cashSessionId}";

        return $this->saveReportAction->execute(
            name: "Cierre de Caja - {$infraName} - {$registerName} - {$date}",
            reference: $reference,
            type: 'barbershop_cash_session_summary',
            data: $this->cashSessionSummaryQuery->toReportData($queryData),
            filePath: $filePath,
        );
    }
}
