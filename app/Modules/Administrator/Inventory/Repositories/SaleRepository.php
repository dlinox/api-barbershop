<?php

namespace App\Modules\Administrator\Inventory\Repositories;

use App\Models\Inventory\ProductPresentation;
use App\Models\Inventory\Sale;
use App\Common\Traits\HasInfrastructureScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleRepository
{
    use HasInfrastructureScope;

    public function getProductsWithStock(int $infrastructureId)
    {
        $this->validateInfrastructureAccess($infrastructureId);
        return ProductPresentation::select(
            'inventory_product_presentations.id',
            'inventory_product_presentations.product_id',
            'inventory_product_presentations.name',
            'inventory_product_presentations.sku',
            'inventory_product_presentations.sale_price',
            'inventory_product_presentations.quantity',

            'inventory_products.id as product_id',
            'inventory_products.name as product_name',

            'inventory_stocks.current_stock',
        )
            ->join('inventory_stocks', 'inventory_product_presentations.id', '=', 'inventory_stocks.presentation_id')
            ->join('inventory_products', 'inventory_product_presentations.product_id', '=', 'inventory_products.id')
            ->where('inventory_stocks.infrastructure_id', $infrastructureId)
            ->where('inventory_product_presentations.is_active', true)
            ->where('inventory_stocks.current_stock', '>', 0)
            ->where('inventory_products.is_active', true)
            ->where('inventory_products.is_for_sale', true)
            ->get();
    }

    public function dataTable(Request $request)
    {
        $query = Sale::select(
            'inventory_sales.*',
            'core_persons.name as person_name',
            'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname',
            'core_persons.document_number as person_document_number',
            'auth_users.username as user_username',
            'treasury_incomes.id as income_id',
            'treasury_cash_sessions.status as cash_session_status',
        )
            ->join('treasury_cash_sessions', 'treasury_cash_sessions.id', 'inventory_sales.cash_session_id')
            ->join('treasury_cash_registers', 'treasury_cash_registers.id', 'treasury_cash_sessions.cash_register_id')
            ->leftJoin('core_persons', 'core_persons.id', 'inventory_sales.person_id')
            ->leftJoin('auth_users', 'auth_users.id', 'inventory_sales.user_id')
            ->leftJoin('treasury_incomes', function ($join) {
                $join->on('treasury_incomes.transactionable_id', '=', 'inventory_sales.id')
                    ->where('treasury_incomes.transactionable_type', '=', 'inventory_sales');
            })->whereNull('inventory_sales.barbershop_ticket_id');

        $this->scopeByInfrastructure($query, 'treasury_cash_registers.infrastructure_id');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('inventory_sales.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function getById(int $id)
    {
        return Sale::with(['items.presentation.product'])
            ->findOrFail($id);
    }

    public function salesOverview(int $cashSessionId): array
    {
        $stats = Sale::selectRaw("
            status,
            COUNT(*) as count,
            COALESCE(SUM(total), 0) as amount
        ")
            ->where('cash_session_id', $cashSessionId)
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $paymentSummary = DB::table('treasury_income_payment_methods as ipm')
            ->join('treasury_incomes as i', 'i.id', '=', 'ipm.income_id')
            ->join('core_payment_methods as pm', 'pm.id', '=', 'ipm.payment_method_id')
            ->where('i.cash_session_id', $cashSessionId)
            ->where('i.status', 'completed')
            ->select(
                'pm.name as method_name',
                'pm.type as method_type',
                DB::raw('COUNT(DISTINCT i.id) as income_count'),
                DB::raw('COALESCE(SUM(ipm.amount), 0) as total'),
            )
            ->groupBy('pm.name', 'pm.type')
            ->orderByDesc('total')
            ->get()
            ->map(fn ($row) => [
                'methodName'  => $row->method_name,
                'methodType'  => $row->method_type,
                'incomeCount' => (int) $row->income_count,
                'total'       => (float) $row->total,
            ])
            ->values()
            ->toArray();

        $paymentSummaryCollection = collect($paymentSummary);
        $cashAmount = (float) $paymentSummaryCollection->where('methodType', 'cash')->sum('total');
        $bankAmount = (float) $paymentSummaryCollection->where('methodType', '!=', 'cash')->sum('total');

        return [
            'completed' => [
                'count'      => (int) ($stats['completed']->count ?? 0),
                'amount'     => (float) ($stats['completed']->amount ?? 0),
                'cashAmount' => $cashAmount,
                'bankAmount' => $bankAmount,
            ],
            'pending' => [
                'count'  => (int) ($stats['pending']->count ?? 0),
                'amount' => (float) ($stats['pending']->amount ?? 0),
            ],
            'cancelled' => [
                'count'  => (int) ($stats['cancelled']->count ?? 0),
                'amount' => (float) ($stats['cancelled']->amount ?? 0),
            ],
            'total' => [
                'count'  => (int) $stats->sum('count'),
                'amount' => (float) $stats->sum('amount'),
            ],
            'paymentSummary' => $paymentSummary,
        ];
    }
}
