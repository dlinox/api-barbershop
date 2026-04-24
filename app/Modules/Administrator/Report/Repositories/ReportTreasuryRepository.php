<?php

namespace App\Modules\Administrator\Report\Repositories;

use App\Models\Treasury\CashSession;
use App\Models\Treasury\Expense;
use App\Models\Treasury\Income;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportTreasuryRepository
{
    public function summary(string $from, string $to, ?int $infrastructureId = null): array
    {
        $incomeQuery = Income::where('status', 'completed')
            ->whereBetween('transaction_date', [$from, $to])
            ->when($infrastructureId, fn($q) => $q->where('infrastructure_id', $infrastructureId));

        $totalIncome = (float) (clone $incomeQuery)->sum('total');
        $incomeCount = (clone $incomeQuery)->count();

        $expenseQuery = Expense::approved()
            ->whereBetween('transaction_date', [$from, $to])
            ->when($infrastructureId, fn($q) => $q->where('infrastructure_id', $infrastructureId));

        $totalExpenses = (float) (clone $expenseQuery)->sum('amount');

        $netProfit = $totalIncome - $totalExpenses;
        $margin = $totalIncome > 0 ? round(($netProfit / $totalIncome) * 100, 1) : 0;

        $pendingExpenses = Expense::pending()
            ->when($infrastructureId, fn($q) => $q->where('infrastructure_id', $infrastructureId))
            ->count();

        return [
            'total_income'     => $totalIncome,
            'income_count'     => $incomeCount,
            'total_expenses'   => $totalExpenses,
            'net_profit'       => $netProfit,
            'margin'           => $margin,
            'pending_expenses' => $pendingExpenses,
        ];
    }

    public function incomeVsExpenses(string $from, string $to, ?int $infrastructureId = null): array
    {
        $incomes = Income::select(
            DB::raw("DATE(transaction_date) as day"),
            DB::raw('SUM(total) as amount'),
        )
            ->where('status', 'completed')
            ->whereBetween('transaction_date', [$from, $to])
            ->when($infrastructureId, fn($q) => $q->where('infrastructure_id', $infrastructureId))
            ->groupBy('day')
            ->pluck('amount', 'day');

        $expenses = Expense::select(
            DB::raw("DATE(transaction_date) as day"),
            DB::raw('SUM(amount) as amount'),
        )
            ->approved()
            ->whereBetween('transaction_date', [$from, $to])
            ->when($infrastructureId, fn($q) => $q->where('infrastructure_id', $infrastructureId))
            ->groupBy('day')
            ->pluck('amount', 'day');

        $start = Carbon::parse($from);
        $end = Carbon::parse($to);
        $categories = [];
        $incomeData = [];
        $expenseData = [];

        while ($start->lte($end)) {
            $dateStr = $start->toDateString();
            $categories[] = $start->format('d');
            $incomeData[] = (float) ($incomes[$dateStr] ?? 0);
            $expenseData[] = (float) ($expenses[$dateStr] ?? 0);
            $start->addDay();
        }

        return [
            'categories' => $categories,
            'series'     => [
                ['name' => 'Ingresos', 'data' => $incomeData],
                ['name' => 'Gastos', 'data' => $expenseData],
            ],
        ];
    }

    public function paymentMethodsDistribution(string $from, string $to, ?int $infrastructureId = null): array
    {
        $methods = DB::table('treasury_income_payment_methods as ipm')
            ->join('treasury_incomes as i', 'i.id', '=', 'ipm.income_id')
            ->join('core_payment_methods as pm', 'pm.id', '=', 'ipm.payment_method_id')
            ->where('i.status', 'completed')
            ->whereBetween('i.transaction_date', [$from, $to])
            ->when($infrastructureId, fn($q) => $q->where('i.infrastructure_id', $infrastructureId))
            ->select(
                'pm.name as method',
                DB::raw('SUM(ipm.amount) as amount'),
            )
            ->groupBy('pm.id', 'pm.name')
            ->orderByDesc('amount')
            ->get();

        return $methods->map(fn($m) => [
            'method' => $m->method,
            'amount' => (float) $m->amount,
        ])->toArray();
    }

    public function expensesByType(string $from, string $to, ?int $infrastructureId = null): array
    {
        $types = DB::table('treasury_expenses as e')
            ->join('treasury_expense_types as et', 'et.id', '=', 'e.expense_type_id')
            ->where('e.status', 'approved')
            ->whereBetween('e.transaction_date', [$from, $to])
            ->when($infrastructureId, fn($q) => $q->where('e.infrastructure_id', $infrastructureId))
            ->select(
                'et.name as type',
                DB::raw('SUM(e.amount) as amount'),
                DB::raw('COUNT(e.id) as count'),
            )
            ->groupBy('et.id', 'et.name')
            ->orderByDesc('amount')
            ->get();

        return $types->map(fn($t) => [
            'type'   => $t->type,
            'amount' => (float) $t->amount,
            'count'  => (int) $t->count,
        ])->toArray();
    }

    public function cashSessionDetail(string $from, string $to, ?int $infrastructureId = null): array
    {
        $sessions = CashSession::with(['cashRegister.infrastructure'])
            ->where('status', 'closed')
            ->whereBetween('opened_at', [$from, $to])
            ->when($infrastructureId, fn($q) => $q->whereHas('cashRegister', fn($cr) => $cr->where('infrastructure_id', $infrastructureId)))
            ->orderByDesc('opened_at')
            ->get();

        return $sessions->map(fn($s) => [
            'register'       => ($s->cashRegister?->infrastructure?->name ?? '') . ' - ' . ($s->cashRegister?->name ?? ''),
            'opened'         => $s->opened_at?->format('Y-m-d H:i'),
            'closed'         => $s->closed_at?->format('Y-m-d H:i'),
            'openingAmount'  => (float) $s->opening_amount,
            'income'         => (float) $s->incomes()->where('status', 'completed')->sum('total'),
            'expenses'       => (float) $s->expenses()->approved()->sum('amount'),
            'expectedClose'  => (float) $s->expected_closing_amount,
            'actualClose'    => (float) $s->actual_closing_amount,
            'difference'     => (float) $s->difference,
        ])->toArray();
    }

    public function selectCashSessions(): Collection
    {
        return DB::table('treasury_cash_sessions as cs')
            ->join('treasury_cash_registers as cr', 'cr.id', '=', 'cs.cash_register_id')
            ->join('core_infrastructures as ci', 'ci.id', '=', 'cr.infrastructure_id')
            ->leftJoin('barbershop_branches as bb', function ($join) {
                $join->on('bb.id', '=', 'ci.infrastructurable_id')
                    ->where('ci.infrastructurable_type', '=', 'barbershop_branches');
            })
            ->leftJoin('academy_branches as ab', function ($join) {
                $join->on('ab.id', '=', 'ci.infrastructurable_id')
                    ->where('ci.infrastructurable_type', '=', 'academy_branches');
            })
            ->select(
                'cs.id as value',
                DB::raw("CONCAT(DATE_FORMAT(cs.opened_at, '%d/%m/%Y'), ' - ', COALESCE(bb.name, ab.name, '---'), ' - ', cr.name) as title"),
            )
            ->orderByDesc('cs.opened_at')
            ->limit(200)
            ->get();
    }
}
