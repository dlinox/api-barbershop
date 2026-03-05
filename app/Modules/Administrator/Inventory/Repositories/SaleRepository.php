<?php

namespace App\Modules\Administrator\Inventory\Repositories;

use App\Models\Inventory\ProductPresentation;
use App\Models\Inventory\Sale;
use Illuminate\Http\Request;

class SaleRepository
{
    /**
     * Obtiene las presentaciones con stock > 0 para una sede (infrastructure).
     */
    public function getProductsWithStock(int $infrastructureId): array
    {
        return ProductPresentation::select([
            'inventory_product_presentations.id',
            'inventory_product_presentations.product_id',
            'inventory_product_presentations.sku',
            'inventory_product_presentations.name as presentation_name',
            'inventory_product_presentations.sale_price',
            'inventory_products.name as product_name',
            'inventory_products.category_id',
            'inventory_stocks.current_stock',
        ])
            ->join('inventory_products', 'inventory_products.id', '=', 'inventory_product_presentations.product_id')
            ->leftJoin('inventory_stocks', function ($join) use ($infrastructureId) {
                $join->on('inventory_product_presentations.id', '=', 'inventory_stocks.presentation_id')
                    ->where('inventory_stocks.infrastructure_id', '=', $infrastructureId);
            })
            ->where(function ($q) {
                $q->where('inventory_stocks.current_stock', '>', 0)
                    ->orWhereNull('inventory_stocks.current_stock');
            })
            ->where('inventory_product_presentations.is_active', true)
            ->get()
            ->toArray();
    }

    public function dataTable(Request $request, int $cashRegisterId)
    {
        $query = Sale::with(['person:id,name,last_name,document_number', 'user:id,username'])
            ->whereHas('cashSession', function ($q) use ($cashRegisterId) {
                $q->where('cash_register_id', $cashRegisterId);
            });

        return $query->dataTable($request);
    }
}
