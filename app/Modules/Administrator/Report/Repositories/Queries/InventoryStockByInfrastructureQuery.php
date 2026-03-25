<?php

namespace App\Modules\Administrator\Report\Repositories\Queries;

use App\Models\Core\Company;
use App\Models\Core\Infrastructure;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InventoryStockByInfrastructureQuery
{
    public function __invoke(int $infrastructureId): array
    {
        $infrastructure = Infrastructure::findOrFail($infrastructureId);

        $rows = DB::table('inventory_stocks as s')
            ->join('inventory_product_presentations as pp', 'pp.id', '=', 's.presentation_id')
            ->join('inventory_products as p', 'p.id', '=', 'pp.product_id')
            ->join('inventory_categories as c', 'c.id', '=', 'p.category_id')
            ->where('s.infrastructure_id', $infrastructureId)
            ->where('p.is_active', true)
            ->select(
                'p.name as product',
                'pp.name as presentation',
                'pp.sku',
                'c.name as category',
                's.current_stock as stock',
                'pp.min_stock',
                'pp.cost_price',
                'pp.sale_price',
            )
            ->orderBy('c.name')
            ->orderBy('p.name')
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
            'status'       => (int) $r->stock <= 0 ? 'Agotado' : ((int) $r->stock < (int) $r->min_stock ? 'Bajo' : 'OK'),
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
            'report_title'        => 'STOCK POR SEDE',
            'report_subtitle'     => 'INVENTARIO',
            'report_date'         => $now->format('d/m/Y'),
            'report_day'          => $dayNames[$now->dayOfWeek],
            'infrastructure_name' => $queryData['infrastructure']->name,
            'rows'                => $queryData['rows'],
            'total_value'         => $queryData['total_value'],
        ];
    }

    public function toReportData(array $queryData): array
    {
        return [
            'infrastructure_id'   => $queryData['infrastructure']->id,
            'infrastructure_name' => $queryData['infrastructure']->name,
            'products_count'      => count($queryData['rows']),
            'total_value'         => $queryData['total_value'],
        ];
    }
}
