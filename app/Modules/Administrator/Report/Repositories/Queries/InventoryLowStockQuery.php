<?php

namespace App\Modules\Administrator\Report\Repositories\Queries;

use App\Models\Core\Company;
use App\Models\Core\Infrastructure;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryLowStockQuery
{
    public function __invoke(?int $infrastructureId = null): array
    {
        $infrastructure = $infrastructureId
            ? Infrastructure::with('infrastructurable')->find($infrastructureId)
            : null;
        $infrastructureName = $infrastructure?->infrastructurable?->name ?? null;

        $query = DB::table('inventory_stocks as s')
            ->join('inventory_product_presentations as pp', 'pp.id', '=', 's.presentation_id')
            ->join('inventory_products as p', 'p.id', '=', 'pp.product_id')
            ->join('inventory_categories as c', 'c.id', '=', 'p.category_id')
            ->leftJoin('core_infrastructures as ci', 'ci.id', '=', 's.infrastructure_id')
            ->leftJoin('barbershop_branches as bb', function ($join) {
                $join->on('ci.infrastructurable_id', '=', 'bb.id')
                     ->where('ci.infrastructurable_type', '=', 'App\\Models\\Barbershop\\Branch');
            })
            ->leftJoin('academy_branches as ab', function ($join) {
                $join->on('ci.infrastructurable_id', '=', 'ab.id')
                     ->where('ci.infrastructurable_type', '=', 'App\\Models\\Academy\\Branch');
            })
            ->where('p.is_active', true)
            ->where(function ($q) {
                $q->whereColumn('s.current_stock', '<', 'pp.min_stock');
            })
            ->when($infrastructureId, fn($q) => $q->where('s.infrastructure_id', $infrastructureId))
            ->select(
                'p.name as product',
                'pp.sku',
                'c.name as category',
                DB::raw("COALESCE(bb.name, ab.name, '---') as infrastructure"),
                's.current_stock as stock',
                'pp.min_stock',
                'pp.cost_price',
            )
            ->orderByRaw('s.current_stock ASC')
            ->get();

        $rows = $query->map(fn($r) => [
            'product'        => $r->product,
            'sku'            => $r->sku,
            'category'       => $r->category,
            'infrastructure' => $r->infrastructure ?? '---',
            'stock'          => (int) $r->stock,
            'min_stock'      => (int) $r->min_stock,
            'deficit'        => (int) $r->min_stock - (int) $r->stock,
            'status'         => (int) $r->stock <= 0 ? 'Agotado' : 'Bajo',
            'restock_cost'   => ((int) $r->min_stock - (int) $r->stock) * (float) $r->cost_price,
        ])->toArray();

        return [
            'infrastructure_name' => $infrastructureName,
            'rows'                => $rows,
            'total_deficit'       => array_sum(array_column($rows, 'deficit')),
            'total_restock_cost'  => array_sum(array_column($rows, 'restock_cost')),
        ];
    }

    public function toBladeData(array $queryData): array
    {
        $now = Carbon::now();
        $dayNames = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        return [
            'company'             => Company::first(),
            'report_title'        => 'PRODUCTOS CON STOCK BAJO / AGOTADO',
            'report_subtitle'     => 'INVENTARIO',
            'report_date'         => $now->format('d/m/Y'),
            'report_day'          => $dayNames[$now->dayOfWeek],
            'infrastructure_name' => $queryData['infrastructure_name'] ?? 'TODAS LAS SEDES',
            'rows'                => $queryData['rows'],
            'total_deficit'       => $queryData['total_deficit'],
            'total_restock_cost'  => $queryData['total_restock_cost'],
        ];
    }

    public function toReportData(array $queryData): array
    {
        return [
            'infrastructure_name' => $queryData['infrastructure_name'] ?? 'Todas',
            'products_count'      => count($queryData['rows']),
            'total_deficit'       => $queryData['total_deficit'],
            'total_restock_cost'  => $queryData['total_restock_cost'],
        ];
    }
}
