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
        return Product::where('is_active', true)->get();
    }
}
