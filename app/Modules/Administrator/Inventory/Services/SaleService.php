<?php

namespace App\Modules\Administrator\Inventory\Services;

use App\Common\Exceptions\ApiException;
use App\Modules\Administrator\Inventory\Repositories\SaleRepository;
use App\Modules\Administrator\Inventory\Repositories\Actions\CreateSaleAction;
use App\Modules\Administrator\Inventory\Repositories\Actions\UpdateSaleAction;
use App\Modules\Administrator\Inventory\Repositories\Actions\EnsureClientProfileAction;
use App\Modules\Administrator\Inventory\Repositories\Actions\RegisterKardexMovementAction;
use App\Modules\Administrator\Treasury\Repositories\Actions\CreateIncomeAction;
use App\Models\Inventory\Sale;
use App\Models\Treasury\Income;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function __construct(
        private SaleRepository $saleRepository,
        private CreateSaleAction $createSaleAction,
        private UpdateSaleAction $updateSaleAction,
        private EnsureClientProfileAction $ensureClientProfileAction,
        private CreateIncomeAction $createIncomeAction,
        private RegisterKardexMovementAction $registerKardexMovementAction,
    ) {}

    public function getProductsWithStock(int $infrastructureId)
    {
        return $this->saleRepository->getProductsWithStock($infrastructureId);
    }

    public function dataTable($request)
    {
        return $this->saleRepository->dataTable($request);
    }

    public function getById(int $id)
    {
        return $this->saleRepository->getById($id);
    }

    public function save(array $data, int $infrastructureId): void
    {
        DB::beginTransaction();
        try {
            $hasIncome = !empty($data['income']);
            $isEdit    = !empty($data['id']);

            if (!empty($data['client_id'])) {
                $this->ensureClientProfileAction->execute($data['client_id']);
            }

            if ($isEdit) {
                $sale = $this->editPendingSale($data, $infrastructureId, $hasIncome);
            } else {
                $sale = $this->createSale($data, $infrastructureId, $hasIncome);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Caso 1: Crear venta nueva.
     * - Sin income → status 'pending', sin kardex/stock.
     * - Con income → status 'completed', con kardex/stock/income.
     */
    private function createSale(array $data, int $infrastructureId, bool $hasIncome): Sale
    {
        $status = $hasIncome ? 'completed' : 'pending';

        $sale = $this->createSaleAction->execute($data, $infrastructureId, $status);

        if ($hasIncome) {
            $this->createIncome($data, $infrastructureId, $sale);
        }

        return $sale;
    }

    /**
     * Caso 2 y 3: Editar venta pendiente.
     * - Sin income → actualiza datos, sigue 'pending'.
     * - Con income → actualiza datos, crea kardex/stock/income, pasa a 'completed'.
     */
    private function editPendingSale(array $data, int $infrastructureId, bool $hasIncome): Sale
    {
        $sale = Sale::findOrFail($data['id']);

        if ($sale->status !== 'pending') {
            throw new ApiException('Solo se puede editar una venta en estado pendiente.');
        }

        $sale = $this->updateSaleAction->execute($data, $sale);

        if ($hasIncome) {
            $sale->update(['status' => 'completed']);

            $this->createSaleAction->registerStockMovements($sale, $data['items'], $infrastructureId);
            $this->createIncome($data, $infrastructureId, $sale);
        }

        return $sale;
    }

    public function delete(int $id): void
    {
        $sale = Sale::findOrFail($id);

        if ($sale->status !== 'pending') {
            throw new ApiException('Solo se puede eliminar una venta en estado pendiente.');
        }

        $sale->items()->delete();
        $sale->delete();
    }

    public function annul(int $id): void
    {
        $sale = Sale::with('items.presentation')->findOrFail($id);

        if ($sale->status !== 'completed') {
            throw new ApiException('Solo se puede anular una venta completada.');
        }

        DB::beginTransaction();
        try {
            foreach ($sale->items as $item) {
                $this->registerKardexMovementAction->execute([
                    'product_id'        => $item->presentation->product_id,
                    'presentation_id'   => $item->presentation_id,
                    'infrastructure_id' => $sale->infrastructure_id,
                    'quantity'          => $item->quantity,
                    'unit_cost'         => $item->unit_price,
                    'movement_type'     => 'in',
                    'reason'            => 'return',
                    'reference_id'      => $sale->id,
                    'reference_type'    => Sale::class,
                    'notes'             => 'Devolución por anulación de venta #' . $sale->id,
                ]);
            }

            $income = Income::where('transactionable_type', 'inventory_sales')
                ->where('transactionable_id', $sale->id)
                ->first();

            if ($income) {
                $income->update(['status' => 'cancelled']);
            }

            $sale->update(['status' => 'cancelled']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function createIncome(array $data, int $infrastructureId, Sale $sale): void
    {
        $incomeData = $data['income'];
        $incomeData['cash_session_id'] = $data['cash_session_id'];

        $this->createIncomeAction->execute(
            data: $incomeData,
            infrastructureId: $infrastructureId,
            transactionableType: 'inventory_sales',
            transactionableId: $sale->id,
        );
    }
}
