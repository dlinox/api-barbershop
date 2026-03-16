<?php

namespace App\Modules\Administrator\Inventory\Repositories;

use App\Models\Inventory\Product;

class StockRepository
{

    public function dataTable($request, $infrastructureId)
    {

        $query = Product::select('inventory_products.*')->distinct()->leftJoin('inventory_product_presentations', 'inventory_products.id', '=', 'inventory_product_presentations.product_id')
            ->leftJoin('inventory_stocks', function ($join) use ($infrastructureId) {
                $join->on('inventory_product_presentations.id', '=', 'inventory_stocks.presentation_id')
                    ->where('inventory_stocks.infrastructure_id', '=', $infrastructureId);
            });

        $items = $query->dataTable($request);
        return $items;
    }
}
