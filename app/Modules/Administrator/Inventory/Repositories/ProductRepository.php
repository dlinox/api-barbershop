<?php

namespace App\Modules\Administrator\Inventory\Repositories;

use App\Models\Inventory\Product;

class ProductRepository
{
    public function dataTable($request)
    {
        return Product::dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        return Product::updateOrCreate(['id' => $data['id']], $data);
    }

    public function delete(int $id)
    {
        $product = Product::findOrFail($id);

        if ($product->kardex()->count() > 0) {
            throw new \Exception('No se puede eliminar el producto porque tiene movimientos en el kardex');
        }

        $product->delete();
        return $product;
    }

    public function getActiveProducts()
    {
        return Product::select(
            'inventory_products.id',
            'inventory_products.name',

            'inventory_product_presentations.id as presentation_id',
            'inventory_product_presentations.name as presentation_name',
            'inventory_product_presentations.unit_type as presentation_unit_type',
            'inventory_product_presentations.quantity as presentation_quantity',

            'inventory_stocks.current_stock as stock_current_stock',
        )
            ->join('inventory_product_presentations', 'inventory_products.id', '=', 'inventory_product_presentations.product_id')
            ->join('inventory_stocks', 'inventory_product_presentations.id', '=', 'inventory_stocks.presentation_id')
            ->where('inventory_products.is_active', true)
            ->distinct()
            ->get();
    }
}
