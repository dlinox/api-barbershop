<?php

namespace App\Modules\Administrator\Report\Repositories\Queries;

use App\Models\Core\Company;
use App\Models\Core\Infrastructure;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryStockByProductQuery
{
    public function __invoke(?int $infrastructureId = null): array
    {
        $infrastructure = $infrastructureId ? Infrastructure::find($infrastructureId) : null;

        $rows = DB::table('inventory_product_presentations as pp')
            ->join('inventory_products as p', 'p.id', '=', 'pp.product_id')
            ->join('inventory_categories as c', 'c.id', '=', 'p.category_id')
            ->leftJoin('inventory_stocks as s', function ($join) use ($infrastructureId) {
                $join->on('s.presentation_id', '=', 'pp.id');
                if ($infrastructureId) {
                    $join->where('s.infrastructure_id', $infrastructureId);
                }
            })
            ->where('p.is_active', true)
            ->where('pp.is_active', true)
            ->select(
                'p.name as product',
                'pp.name as presentation',
                'pp.sku',
                'c.name as category',
                DB::raw('COALESCE(SUM(s.current_stock), 0) as stock'),
                'pp.min_stock',
                'pp.cost_price',
                'pp.sale_price',
            )
            ->groupBy('p.id', 'p.name', 'pp.id', 'pp.name', 'pp.sku', 'c.name', 'pp.min_stock', 'pp.cost_price', 'pp.sale_price')
            ->orderBy('p.name')
            ->orderBy('pp.name')
            ->get();

        $data = $rows->map(fn($r) => [
            'product'      => $r->product,
            'presentation' => $r->presentation ?? $r->sku,
            'sku'          => $r->sku,
            'category'     => $r->category,
            'stock'        => (int) $r->stock,
            'min_stock'    => (int) $r->min_stock,
            'cost_price'   => (float) $r->cost_price,
            'sale_price'   => (float) $r->sale_price,
            'stock_value'  => (int) $r->stock * (float) $r->cost_price,
        ])->toArray();

        return [
            'infrastructure' => $infrastructure,
            'rows'           => $data,
            'total_value'    => array_sum(array_column($data, 'stock_value')),
        ];
    }

    public function toBladeData(array $queryData): array
    {
        $now = Carbon::now();
        $dayNames = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        return [
            'company'             => Company::first(),
            'report_title'        => 'STOCK POR PRODUCTO',
            'report_subtitle'     => 'INVENTARIO',
            'report_date'         => $now->format('d/m/Y'),
            'report_day'          => $dayNames[$now->dayOfWeek],
            'infrastructure_name' => $queryData['infrastructure']?->name ?? 'TODAS LAS SEDES',
            'rows'                => $queryData['rows'],
            'total_value'         => $queryData['total_value'],
        ];
    }

    public function toReportData(array $queryData): array
    {
        return [
            'infrastructure_id'   => $queryData['infrastructure']?->id,
            'infrastructure_name' => $queryData['infrastructure']?->name ?? 'Todas',
            'products_count'      => count($queryData['rows']),
            'total_value'         => $queryData['total_value'],
        ];
    }
}
