<?php

namespace App\Modules\Administrator\Inventory\Repositories;

use App\Models\Inventory\Product;
use App\Models\Inventory\Stock;

class StockRepository
{
    public function dataTable($request, $infrastructureId)
    {
        $query = Stock::select(
            'inventory_stocks.id',
            'inventory_stocks.current_stock',
            'inventory_stocks.last_movement_at',
            
            'inventory_products.id as product_id',
            'inventory_products.name as product_name',
            'inventory_products.sku as product_sku',
            'inventory_products.min_stock as product_min_stock',
            'inventory_products.max_stock as product_max_stock',

            'inventory_stocks.infrastructure_id',

        )
            ->rightJoin('inventory_products', 'inventory_stocks.product_id', '=', 'inventory_products.id')
            ->leftJoin('core_infrastructures', 'inventory_stocks.infrastructure_id', '=', 'core_infrastructures.id')
            ->where(function ($query) use ($infrastructureId) {
                $query->where('inventory_stocks.infrastructure_id', $infrastructureId)
                    ->orWhereNull('inventory_stocks.infrastructure_id');
            });


        $items = $query->dataTable($request);

        return $items;
    }
}
