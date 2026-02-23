<?php

namespace App\Modules\Administrator\Inventory\Services;

use App\Common\Exceptions\ApiException;
use App\Modules\Administrator\Inventory\Repositories\KardexRepository;
use App\Modules\Administrator\Inventory\Repositories\StockRepository;
use App\Modules\Administrator\Inventory\Repositories\Actions\RegisterKardexMovementAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function __construct(
        private StockRepository $stockRepository,
        private KardexRepository $kardexRepository,
        private RegisterKardexMovementAction $registerKardexMovementAction,
    ) {}

    public function dataTable(Request $request, $infrastructureId)
    {
        return $this->stockRepository->dataTable($request, $infrastructureId);
    }

    public function initializeStock(array $data): void
    {
        $lastBalance = $this->kardexRepository->getLastBalance($data['product_id'], $data['infrastructure_id']);
        if ($lastBalance) throw new ApiException('El producto ya tiene stock inicializado en esta sucursal');

        DB::beginTransaction();
        try {
            $this->registerKardexMovementAction->execute([
                ...$data,
                'movement_type' => 'in',
                'reason'        => 'initial',
            ]);

            DB::commit();
        } catch (ApiException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage());
        }
    }

    public function adjustStock(array $data): void
    {

        if (!isset($data['unit_cost'])) {
            $lastBalance = $this->kardexRepository->getLastBalance($data['product_id'], $data['infrastructure_id']);
            if (!$lastBalance) throw new ApiException('El producto no tiene stock en esta sucursal, debe inicializarlo primero');
            $data['unit_cost'] = $lastBalance->balance_unit_cost;
        }

        DB::beginTransaction();
        try {
            $this->registerKardexMovementAction->execute([
                ...$data,
                'movement_type' => 'adjustment',
                'reason'        => 'adjustment',
            ]);

            DB::commit();
        } catch (ApiException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage());
        }
    }
}
