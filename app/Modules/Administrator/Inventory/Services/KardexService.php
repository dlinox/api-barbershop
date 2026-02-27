<?php

namespace App\Modules\Administrator\Inventory\Services;

use App\Modules\Administrator\Inventory\Repositories\KardexRepository;
use Illuminate\Http\Request;

class KardexService
{
    public function __construct(
        private KardexRepository $kardexRepository
    ) {}

    public function dataTable(Request $request)
    {
        //
    }

    public function registerMovement(array $data)
    {
        //
    }

    public function getLastBalance(int $presentationId, int $branchId)
    {
        //
    }

    public function getByPresentation(int $presentationId, int $branchId)
    {
        //
    }
}
