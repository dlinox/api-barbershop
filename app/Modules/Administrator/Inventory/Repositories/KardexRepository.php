<?php

namespace App\Modules\Administrator\Inventory\Repositories;

use App\Models\Inventory\Kardex;

class KardexRepository
{
    public function dataTable($request)
    {
        //
    }

    public function create(array $data): Kardex
    {
        return Kardex::create($data);
    }

    public function getLastBalance(int $productId, int $infrastructureId): ?Kardex
    {
        return Kardex::where('product_id', $productId)
            ->where('infrastructure_id', $infrastructureId)
            ->orderByDesc('id')
            ->first();
    }

    public function getByProduct(int $productId, int $branchId)
    {
        //
    }
}
