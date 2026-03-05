<?php

namespace App\Modules\Administrator\Treasury\Repositories;

use App\Models\Treasury\CashSession;

class CashSessionRepository
{
    public function dataTable($request, int $cashRegisterId)
    {
        $query = CashSession::with(['openedByUser', 'closedByUser'])
            ->where('cash_register_id', $cashRegisterId)
            ->orderBy('opened_at', 'desc');

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

            $session->expected_closing_amount = (float) $session->opening_amount + (float) $totalIncomes;
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
        $difference = $actualClosingAmount - (float) $session->expected_closing_amount;

        $session->update([
            'closed_by'              => $userId,
            'actual_closing_amount'  => $actualClosingAmount,
            'difference'             => $difference,
            'status'                 => 'closed',
            'closed_at'              => now(),
            'notes'                  => $notes ?? $session->notes,
        ]);

        return $session->fresh(['openedByUser', 'closedByUser']);
    }
}
