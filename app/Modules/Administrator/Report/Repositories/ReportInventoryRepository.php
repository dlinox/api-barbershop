<?php

namespace App\Modules\Administrator\Report\Repositories;

use App\Models\Inventory\Product;
use App\Models\Inventory\PurchaseOrder;
use App\Models\Inventory\Sale;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportInventoryRepository
{
    public function summary(string $from, string $to, ?int $infrastructureId = null): array
    {
        $totalProducts = Product::where('is_active', true)->count();
        $totalCategories = Product::where('is_active', true)->distinct('category_id')->count('category_id');

        $stockQuery = DB::table('inventory_stocks as s')
            ->join('inventory_product_presentations as pp', 'pp.id', '=', 's.presentation_id')
            ->when($infrastructureId, fn($q) => $q->where('s.infrastructure_id', $infrastructureId));

        $lowStock = (clone $stockQuery)
            ->whereColumn('s.current_stock', '<', 'pp.min_stock')
            ->where('s.current_stock', '>', 0)
            ->count();

        $outOfStock = (clone $stockQuery)
            ->where('s.current_stock', '<=', 0)
            ->count();

        $salesQuery = Sale::where('status', 'completed')
            ->whereBetween('created_at', [$from, $to])
            ->when($infrastructureId, fn($q) => $q->where('infrastructure_id', $infrastructureId));

        $totalSales = (float) (clone $salesQuery)->sum('total');

        $pendingOrders = PurchaseOrder::where('status', 'pending')
            ->when($infrastructureId, fn($q) => $q->where('infrastructure_id', $infrastructureId))
            ->count();

        $pendingOrdersAmount = (float) PurchaseOrder::where('status', 'pending')
            ->when($infrastructureId, fn($q) => $q->where('infrastructure_id', $infrastructureId))
            ->sum('total_amount');

        return [
            'total_products'        => $totalProducts,
            'total_categories'      => $totalCategories,
            'low_stock'             => $lowStock,
            'out_of_stock'          => $outOfStock,
            'total_sales'           => $totalSales,
            'pending_orders'        => $pendingOrders,
            'pending_orders_amount' => $pendingOrdersAmount,
        ];
    }

    public function salesTrend(string $from, string $to, ?int $infrastructureId = null): array
    {
        $sales = Sale::select(
            DB::raw("DATE(created_at) as day"),
            DB::raw('SUM(total) as revenue'),
        )
            ->where('status', 'completed')
            ->whereBetween('created_at', [$from, $to])
            ->when($infrastructureId, fn($q) => $q->where('infrastructure_id', $infrastructureId))
            ->groupBy('day')
            ->pluck('revenue', 'day');

        $start = Carbon::parse($from);
        $end = Carbon::parse($to);
        $categories = [];
        $data = [];

        while ($start->lte($end)) {
            $dateStr = $start->toDateString();
            $categories[] = $start->format('d');
            $data[] = (float) ($sales[$dateStr] ?? 0);
            $start->addDay();
        }

        return [
            'categories' => $categories,
            'series'     => [['name' => 'Ventas', 'data' => $data]],
        ];
    }

    public function stockStatus(?int $infrastructureId = null): array
    {
        $query = DB::table('inventory_stocks as s')
            ->join('inventory_product_presentations as pp', 'pp.id', '=', 's.presentation_id')
            ->when($infrastructureId, fn($q) => $q->where('s.infrastructure_id', $infrastructureId));

        $ok = (clone $query)->whereColumn('s.current_stock', '>=', 'pp.min_stock')->count();
        $low = (clone $query)->whereColumn('s.current_stock', '<', 'pp.min_stock')->where('s.current_stock', '>', 0)->count();
        $outOfStock = (clone $query)->where('s.current_stock', '<=', 0)->count();

        return [
            ['status' => 'OK', 'count' => $ok],
            ['status' => 'Bajo', 'count' => $low],
            ['status' => 'Agotado', 'count' => $outOfStock],
        ];
    }

    public function topProducts(string $from, string $to, ?int $infrastructureId = null): array
    {
        $products = DB::table('inventory_sale_items as si')
            ->join('inventory_sales as s', 's.id', '=', 'si.sale_id')
            ->join('inventory_product_presentations as pp', 'pp.id', '=', 'si.presentation_id')
            ->join('inventory_products as p', 'p.id', '=', 'pp.product_id')
            ->where('s.status', 'completed')
            ->whereBetween('s.created_at', [$from, $to])
            ->when($infrastructureId, fn($q) => $q->where('s.infrastructure_id', $infrastructureId))
            ->select(
                'p.name as product',
                DB::raw('SUM(si.total) as revenue'),
                DB::raw('SUM(si.quantity) as quantity'),
            )
            ->groupBy('p.id', 'p.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        return $products->map(fn($p) => [
            'product'  => $p->product,
            'revenue'  => (float) $p->revenue,
            'quantity' => (int) $p->quantity,
        ])->toArray();
    }

    public function purchaseOrderStatus(?int $infrastructureId = null): array
    {
        $statuses = PurchaseOrder::select('status', DB::raw('COUNT(*) as count'))
            ->when($infrastructureId, fn($q) => $q->where('infrastructure_id', $infrastructureId))
            ->groupBy('status')
            ->pluck('count', 'status');

        return [
            'pending'   => (int) ($statuses['pending'] ?? 0),
            'received'  => (int) ($statuses['received'] ?? 0),
            'cancelled' => (int) ($statuses['cancelled'] ?? 0),
        ];
    }

    public function productDetail(?int $infrastructureId = null): array
    {
        $products = DB::table('inventory_product_presentations as pp')
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
                'pp.sku',
                'c.name as category',
                DB::raw('COALESCE(s.current_stock, 0) as stock'),
                'pp.min_stock',
                'pp.cost_price',
                'pp.sale_price',
                DB::raw('CASE WHEN pp.sale_price > 0 THEN ROUND(((pp.sale_price - pp.cost_price) / pp.sale_price) * 100, 0) ELSE 0 END as margin'),
            )
            ->orderBy('p.name')
            ->orderBy('pp.name')
            ->get();

        return $products->map(fn($p) => [
            'product'   => $p->product,
            'sku'       => $p->sku,
            'category'  => $p->category,
            'stock'     => (int) $p->stock,
            'minStock'  => (int) $p->min_stock,
            'cost'      => (float) $p->cost_price,
            'salePrice' => (float) $p->sale_price,
            'margin'    => (int) $p->margin,
        ])->toArray();
    }
}
