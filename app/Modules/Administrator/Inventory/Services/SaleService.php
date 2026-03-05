<?php

namespace App\Modules\Administrator\Inventory\Services;

use App\Modules\Administrator\Inventory\Repositories\SaleRepository;
use App\Modules\Administrator\Inventory\Repositories\Actions\CreateSaleAction;
use App\Modules\Administrator\Treasury\Repositories\Actions\CreateIncomeAction;
use App\Models\Inventory\Sale;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function __construct(
        private SaleRepository $saleRepository,
        private CreateSaleAction $createSaleAction,
        private CreateIncomeAction $createIncomeAction,
    ) {}

    public function getProductsWithStock(int $infrastructureId): array
    {
        return $this->saleRepository->getProductsWithStock($infrastructureId);
    }

    public function dataTable($request, int $cashRegisterId)
    {
        return $this->saleRepository->dataTable($request, $cashRegisterId);
    }

    public function save(array $data, int $infrastructureId): void
    {
        DB::beginTransaction();
        try {
            // 1. Crear venta + ítems + kardex (descuento stock)
            $sale = $this->createSaleAction->execute($data, $infrastructureId);

            // 2. Crear ingreso en treasury (income + details + payment methods)
            $incomeData = $data['income'];
            $incomeData['cash_session_id'] = $data['cash_session_id'];

            $this->createIncomeAction->execute(
                data: $incomeData,
                infrastructureId: $infrastructureId,
                transactionableType: 'inventory_sales',
                transactionableId: $sale->id,
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
