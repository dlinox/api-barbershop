<?php

namespace App\Modules\Administrator\Inventory\Repositories;

use App\Models\Inventory\ProductPresentation;

class ProductPresentationRepository
{
    public function dataTable($request)
    {
        return ProductPresentation::dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        return ProductPresentation::updateOrCreate(['id' => $data['id']], $data);
    }

    public function delete(int $id)
    {
        $presentation = ProductPresentation::findOrFail($id);
        $presentation->delete();
        return $presentation;
    }

    public function getByProduct(int $productId)
    {
        return ProductPresentation::where('product_id', $productId)
            ->where('is_active', true)
            ->get();
    }
}
