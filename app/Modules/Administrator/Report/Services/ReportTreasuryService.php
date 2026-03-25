<?php

namespace App\Modules\Administrator\Report\Services;

use App\Models\Reports\Report;
use App\Modules\Administrator\Report\Repositories\Actions\GenerateReportPdfAction;
use App\Modules\Administrator\Report\Repositories\Actions\SaveReportAction;
use App\Modules\Administrator\Report\Repositories\Queries\ReportDataTableQuery;
use App\Modules\Administrator\Report\Repositories\Queries\TreasuryCashSessionQuery;
use App\Modules\Administrator\Report\Repositories\Queries\TreasuryExpensePerDayQuery;
use App\Modules\Administrator\Report\Repositories\Queries\TreasuryIncomePerDayQuery;
use App\Modules\Administrator\Report\Repositories\Queries\TreasuryIncomeVsExpenseQuery;
use App\Modules\Administrator\Report\Repositories\Queries\TreasuryPendingExpensesQuery;
use App\Modules\Administrator\Report\Repositories\ReportTreasuryRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportTreasuryService
{
    public function __construct(
        private readonly ReportTreasuryRepository $repository,
        private readonly TreasuryIncomePerDayQuery $incomePerDayQuery,
        private readonly TreasuryExpensePerDayQuery $expensePerDayQuery,
        private readonly TreasuryCashSessionQuery $cashSessionQuery,
        private readonly TreasuryIncomeVsExpenseQuery $incomeVsExpenseQuery,
        private readonly TreasuryPendingExpensesQuery $pendingExpensesQuery,
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

    public function incomeVsExpenses(Request $request): array
    {
        [$from, $to] = $this->getDateRange($request);
        $infrastructureId = $request->input('infrastructure_id') ? (int) $request->input('infrastructure_id') : null;

        return $this->repository->incomeVsExpenses($from, $to, $infrastructureId);
    }

    public function paymentMethodsDistribution(Request $request): array
    {
        [$from, $to] = $this->getDateRange($request);
        $infrastructureId = $request->input('infrastructure_id') ? (int) $request->input('infrastructure_id') : null;

        return $this->repository->paymentMethodsDistribution($from, $to, $infrastructureId);
    }

    public function expensesByType(Request $request): array
    {
        [$from, $to] = $this->getDateRange($request);
        $infrastructureId = $request->input('infrastructure_id') ? (int) $request->input('infrastructure_id') : null;

        return $this->repository->expensesByType($from, $to, $infrastructureId);
    }

    public function cashSessionDetail(Request $request): array
    {
        [$from, $to] = $this->getDateRange($request);
        $infrastructureId = $request->input('infrastructure_id') ? (int) $request->input('infrastructure_id') : null;

        return $this->repository->cashSessionDetail($from, $to, $infrastructureId);
    }

    // ─── DataTables ───

    public function dataTableIncomePerDay(Request $request): array
    {
        return ($this->reportDataTableQuery)($request, 'treasury_income_per_day');
    }

    public function dataTableExpensePerDay(Request $request): array
    {
        return ($this->reportDataTableQuery)($request, 'treasury_expense_per_day');
    }

    public function dataTableCashSession(Request $request): array
    {
        return ($this->reportDataTableQuery)($request, 'treasury_cash_session');
    }

    public function dataTableIncomeVsExpense(Request $request): array
    {
        return ($this->reportDataTableQuery)($request, 'treasury_income_vs_expense');
    }

    public function dataTablePendingExpenses(Request $request): array
    {
        return ($this->reportDataTableQuery)($request, 'treasury_pending_expenses');
    }

    // ─── PDF Generation ───

    public function generateIncomePerDayPdf(Request $request): Report
    {
        $infrastructureId = (int) $request->input('infrastructure_id');
        $date = $request->input('date');

        $queryData = ($this->incomePerDayQuery)($infrastructureId, $date);
        $bladeData = $this->incomePerDayQuery->toBladeData($queryData);

        $filePath = $this->generatePdfAction->execute(
            bodyView: 'reports.treasury.income-per-day',
            headerView: 'reports.common.header',
            footerView: 'reports.common.footer',
            data: $bladeData,
            folder: 'treasury',
            filenameBase: "ingreso-diario-tesoreria-{$date}",
        );

        $infraName = $queryData['infrastructure']->name;
        $reference = "treasury_income_per_day,{$infrastructureId},{$date}";

        return $this->saveReportAction->execute(
            name: "Ingreso Diario - {$infraName} - {$date}",
            reference: $reference,
            type: 'treasury_income_per_day',
            data: $this->incomePerDayQuery->toReportData($queryData),
            filePath: $filePath,
        );
    }

    public function generateExpensePerDayPdf(Request $request): Report
    {
        $infrastructureId = (int) $request->input('infrastructure_id');
        $date = $request->input('date');

        $queryData = ($this->expensePerDayQuery)($infrastructureId, $date);
        $bladeData = $this->expensePerDayQuery->toBladeData($queryData);

        $filePath = $this->generatePdfAction->execute(
            bodyView: 'reports.treasury.expense-per-day',
            headerView: 'reports.common.header',
            footerView: 'reports.common.footer',
            data: $bladeData,
            folder: 'treasury',
            filenameBase: "gasto-diario-tesoreria-{$date}",
        );

        $infraName = $queryData['infrastructure']->name;
        $reference = "treasury_expense_per_day,{$infrastructureId},{$date}";

        return $this->saveReportAction->execute(
            name: "Gasto Diario - {$infraName} - {$date}",
            reference: $reference,
            type: 'treasury_expense_per_day',
            data: $this->expensePerDayQuery->toReportData($queryData),
            filePath: $filePath,
        );
    }

    public function generateCashSessionPdf(Request $request): Report
    {
        $cashSessionId = (int) $request->input('cash_session_id');

        $queryData = ($this->cashSessionQuery)($cashSessionId);
        $bladeData = $this->cashSessionQuery->toBladeData($queryData);

        $session = $queryData['session'];
        $date = $session->opened_at ? Carbon::parse($session->opened_at)->toDateString() : now()->toDateString();

        $filePath = $this->generatePdfAction->execute(
            bodyView: 'reports.treasury.cash-session',
            headerView: 'reports.common.header',
            footerView: 'reports.common.footer',
            data: $bladeData,
            folder: 'treasury',
            filenameBase: "cierre-caja-{$cashSessionId}-{$date}",
        );

        $infraName = $queryData['infrastructure_name'];
        $registerName = $queryData['register_name'];
        $reference = "treasury_cash_session,{$cashSessionId}";

        return $this->saveReportAction->execute(
            name: "Cierre de Caja - {$infraName} - {$registerName} - {$date}",
            reference: $reference,
            type: 'treasury_cash_session',
            data: $this->cashSessionQuery->toReportData($queryData),
            filePath: $filePath,
        );
    }

    public function generateIncomeVsExpensePdf(Request $request): Report
    {
        $infrastructureId = (int) $request->input('infrastructure_id');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $queryData = ($this->incomeVsExpenseQuery)($infrastructureId, $dateFrom, $dateTo);
        $bladeData = $this->incomeVsExpenseQuery->toBladeData($queryData);

        $filePath = $this->generatePdfAction->execute(
            bodyView: 'reports.treasury.income-vs-expense',
            headerView: 'reports.common.header',
            footerView: 'reports.common.footer',
            data: $bladeData,
            folder: 'treasury',
            filenameBase: "ingresos-vs-gastos-{$dateFrom}-{$dateTo}",
        );

        $infraName = $queryData['infrastructure']->name;
        $reference = "treasury_income_vs_expense,{$infrastructureId},{$dateFrom},{$dateTo}";

        return $this->saveReportAction->execute(
            name: "Ingresos vs Gastos - {$infraName} - {$dateFrom} a {$dateTo}",
            reference: $reference,
            type: 'treasury_income_vs_expense',
            data: $this->incomeVsExpenseQuery->toReportData($queryData),
            filePath: $filePath,
        );
    }

    public function generatePendingExpensesPdf(Request $request): Report
    {
        $infrastructureId = $request->input('infrastructure_id') ? (int) $request->input('infrastructure_id') : null;

        $queryData = ($this->pendingExpensesQuery)($infrastructureId);
        $bladeData = $this->pendingExpensesQuery->toBladeData($queryData);

        $filePath = $this->generatePdfAction->execute(
            bodyView: 'reports.treasury.pending-expenses',
            headerView: 'reports.common.header',
            footerView: 'reports.common.footer',
            data: $bladeData,
            folder: 'treasury',
            filenameBase: "gastos-pendientes-" . now()->toDateString(),
        );

        $reference = "treasury_pending_expenses," . ($infrastructureId ?? 'all') . ',' . now()->toDateString();

        return $this->saveReportAction->execute(
            name: "Gastos Pendientes - " . ($queryData['infrastructure']?->name ?? 'Todas') . ' - ' . now()->toDateString(),
            reference: $reference,
            type: 'treasury_pending_expenses',
            data: $this->pendingExpensesQuery->toReportData($queryData),
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
