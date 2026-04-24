<?php

namespace App\Modules\BarbershopPanel\Report\Repositories;

use App\Common\Http\Context\AdminContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class ReportRepository
{
    public function selectCashSessions(): Collection
    {
        $infraId = AdminContext::infrastructureId();

        return DB::table('treasury_cash_sessions as cs')
            ->join('treasury_cash_registers as cr', 'cr.id', '=', 'cs.cash_register_id')
            ->where('cr.infrastructure_id', $infraId)
            ->select(
                'cs.id as value',
                DB::raw("CONCAT(DATE_FORMAT(cs.opened_at, '%d/%m/%Y'), ' - ', cr.name) as title"),
            )
            ->orderByDesc('cs.opened_at')
            ->limit(200)
            ->get();
    }
}
