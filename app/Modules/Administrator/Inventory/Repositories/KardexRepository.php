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

    public function getLastBalance(int $presentationId, int $infrastructureId): ?Kardex
    {
        return Kardex::where('presentation_id', $presentationId)
            ->where('infrastructure_id', $infrastructureId)
            ->orderByDesc('id')
            ->first();
    }

    public function getByPresentation(int $presentationId, int $branchId)
    {
        //
    }
}
