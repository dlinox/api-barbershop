<?php

namespace App\Modules\BarberPanel\CashRegister\Repositories;

use App\Models\Core\Infrastructure;
use App\Models\Treasury\CashRegister;
use App\Models\Treasury\CashSession;

class CashRegisterRepository
{
    public function getByBranchId(int $branchId)
    {
        $infrastructure = Infrastructure::where('infrastructurable_type', 'barbershop_branches')
            ->where('infrastructurable_id', $branchId)
            ->first();

        if (!$infrastructure) {
            return collect();
        }

        return CashRegister::where('infrastructure_id', $infrastructure->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function getOpenSession(int $cashRegisterId): ?CashSession
    {
        return CashSession::where('cash_register_id', $cashRegisterId)
            ->where('status', 'open')
            ->with(['openedByUser', 'cashRegister'])
            ->latest('opened_at')
            ->first();
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
}
