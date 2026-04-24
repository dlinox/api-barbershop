<?php

namespace App\Modules\BarberPanel\Pos\Services;

use App\Common\Exceptions\ApiException;
use App\Models\Inventory\Sale;
use App\Models\Treasury\Income;
use App\Modules\Administrator\Inventory\Repositories\Actions\CreateSaleAction;
use App\Modules\Administrator\Inventory\Repositories\Actions\EnsureClientProfileAction;
use App\Modules\Administrator\Inventory\Repositories\Actions\RegisterKardexMovementAction;
use App\Modules\Administrator\Inventory\Repositories\Actions\UpdateSaleAction;
use App\Modules\Administrator\Treasury\Repositories\Actions\CreateIncomeAction;
use App\Modules\BarberPanel\Pos\Repositories\SaleRepository;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function __construct(
        private readonly SaleRepository $saleRepository,
        private readonly CreateSaleAction $createSaleAction,
        private readonly UpdateSaleAction $updateSaleAction,
        private readonly EnsureClientProfileAction $ensureClientProfileAction,
        private readonly CreateIncomeAction $createIncomeAction,
        private readonly RegisterKardexMovementAction $registerKardexMovementAction,
    ) {}

    public function getProductsWithStock(int $infrastructureId)
    {
        return $this->saleRepository->getProductsWithStock($infrastructureId);
    }

    public function dataTable($request)
    {
        return $this->saleRepository->dataTable($request);
    }

    public function getById(int $id): Sale
    {
        return $this->saleRepository->getById($id);
    }

    public function salesOverview(int $cashSessionId): array
    {
        return $this->saleRepository->salesOverview($cashSessionId);
    }

    public function save(array $data, int $infrastructureId): array
    {
        $income = null;

        DB::beginTransaction();
        try {
            $hasIncome = !empty($data['income']);
            $isEdit    = !empty($data['id']);

            if (!empty($data['client_id'])) {
                $this->ensureClientProfileAction->execute($data['client_id']);
            }

            if ($isEdit) {
                [$sale, $income] = $this->editPendingSale($data, $infrastructureId, $hasIncome);
            } else {
                [$sale, $income] = $this->createSale($data, $infrastructureId, $hasIncome);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return ['incomeId' => $income?->id];
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
        $sale = Sale::with(['items.presentation', 'cashSession'])->findOrFail($id);

        if ($sale->status !== 'completed') {
            throw new ApiException('Solo se puede anular una venta completada.');
        }

        if ($sale->cashSession && $sale->cashSession->status === 'closed') {
            throw new ApiException('No se puede anular una venta de una sesión de caja cerrada.');
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

    private function createSale(array $data, int $infrastructureId, bool $hasIncome): array
    {
        $status = $hasIncome ? 'completed' : 'pending';
        $income = null;

        $sale = $this->createSaleAction->execute($data, $infrastructureId, $status);

        if ($hasIncome) {
            $income = $this->createIncome($data, $infrastructureId, $sale);
        }

        return [$sale, $income];
    }

    private function editPendingSale(array $data, int $infrastructureId, bool $hasIncome): array
    {
        $sale = Sale::findOrFail($data['id']);
        $income = null;

        if ($sale->status !== 'pending') {
            throw new ApiException('Solo se puede editar una venta en estado pendiente.');
        }

        $sale = $this->updateSaleAction->execute($data, $sale);

        if ($hasIncome) {
            $sale->update(['status' => 'completed']);
            $this->createSaleAction->registerStockMovements($sale, $data['items'], $infrastructureId);
            $income = $this->createIncome($data, $infrastructureId, $sale);
        }

        return [$sale, $income];
    }

    private function createIncome(array $data, int $infrastructureId, Sale $sale): Income
    {
        $incomeData = $data['income'];
        $incomeData['cash_session_id'] = $data['cash_session_id'];

        return $this->createIncomeAction->execute(
            data: $incomeData,
            infrastructureId: $infrastructureId,
            transactionableType: 'inventory_sales',
            transactionableId: $sale->id,
        );
    }
}
