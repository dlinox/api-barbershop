<?php

namespace App\Modules\Administrator\Treasury\Repositories;

use App\Models\Treasury\CashSession;
use App\Common\Traits\HasInfrastructureScope;

class CashSessionRepository
{
    use HasInfrastructureScope;

    public function dataTable($request)
    {
        $query = CashSession::with(['openedByUser', 'closedByUser'])
            ->join('treasury_cash_registers', 'treasury_cash_registers.id', '=', 'treasury_cash_sessions.cash_register_id')
            ->select('treasury_cash_sessions.*');

        $this->scopeByInfrastructure($query, 'treasury_cash_registers.infrastructure_id');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('treasury_cash_sessions.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function getOpenSession(int $cashRegisterId): ?CashSession
    {
        $session = CashSession::where('cash_register_id', $cashRegisterId)
            ->where('status', 'open')
            ->first();

        if ($session) {
            $totalIncomes = \App\Models\Treasury\Income::where('cash_session_id', $session->id)
                ->where('status', 'completed')
                ->sum('total');

            $totalExpenses = \App\Models\Treasury\Expense::where('cash_session_id', $session->id)
                ->where('status', 'approved')
                ->sum('amount');

            $session->expected_closing_amount = (float) $session->opening_amount + (float) $totalIncomes - (float) $totalExpenses;
        }

        return $session;
    }

    public function openSession(int $cashRegisterId, float $openingAmount, int $userId, ?string $notes): CashSession
    {
        return CashSession::create([
            'cash_register_id'         => $cashRegisterId,
            'opened_by'               => $userId,
            'opening_amount'          => $openingAmount,
            'expected_closing_amount' => $openingAmount,
            'status'                  => 'open',
            'opened_at'               => now(),
            'notes'                   => $notes,
        ]);
    }

    public function closeSession(CashSession $session, float $actualClosingAmount, int $userId, ?string $notes): CashSession
    {
        $totalIncomes = \App\Models\Treasury\Income::where('cash_session_id', $session->id)
            ->where('status', 'completed')
            ->sum('total');

        $totalExpenses = \App\Models\Treasury\Expense::where('cash_session_id', $session->id)
            ->where('status', 'approved')
            ->sum('amount');

        $expectedClosingAmount = (float) $session->opening_amount + (float) $totalIncomes - (float) $totalExpenses;
        $difference = $actualClosingAmount - $expectedClosingAmount;

        $session->update([
            'closed_by'              => $userId,
            'expected_closing_amount' => $expectedClosingAmount,
            'actual_closing_amount'  => $actualClosingAmount,
            'difference'             => $difference,
            'status'                 => 'closed',
            'closed_at'              => now(),
            'notes'                  => $notes ?? $session->notes,
        ]);

        return $session->fresh(['openedByUser', 'closedByUser']);
    }
}
