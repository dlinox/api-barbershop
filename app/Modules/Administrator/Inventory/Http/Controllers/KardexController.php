<?php

namespace App\Modules\Administrator\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Inventory\Services\KardexService;
use App\Modules\Administrator\Inventory\Http\Resources\Kardex\KardexDataTableItemResource;

class KardexController
{
    public function __construct(
        private KardexService $kardexService
    ) {}

    public function dataTable(Request $request)
    {
        //
    }

    public function registerMovement(Request $request)
    {
        //
    }

    public function getByProduct(int $productId, int $branchId)
    {
        //
    }
}
