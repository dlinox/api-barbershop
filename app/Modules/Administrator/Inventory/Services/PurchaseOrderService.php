<?php

namespace App\Modules\Administrator\Inventory\Services;

use App\Modules\Administrator\Inventory\Repositories\PurchaseOrderRepository;
use App\Modules\Administrator\Inventory\Repositories\Actions\ReceivePurchaseOrderAction;
use App\Modules\Administrator\Inventory\Repositories\Actions\CancelPurchaseOrderAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderService
{
    public function __construct(
        private PurchaseOrderRepository $purchaseOrderRepository,
        private ReceivePurchaseOrderAction $receivePurchaseOrderAction,
        private CancelPurchaseOrderAction $cancelPurchaseOrderAction,
    ) {}

    public function dataTable(Request $request)
    {
        return $this->purchaseOrderRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return DB::transaction(function () use ($data) {

            // Si es edición, verificar que esté en estado pendiente
            if (isset($data['id'])) {
                $existing = $this->purchaseOrderRepository->getById($data['id']);
                if ($existing->status !== 'pending') {
                    throw new \Exception('Solo se pueden editar órdenes en estado pendiente');
                }
            }

            $orderData = collect($data)->except('items')->toArray();
            $order = $this->purchaseOrderRepository->createOrUpdate($orderData);

            if (isset($data['items'])) {
                $order->items()->delete();
                foreach ($data['items'] as $item) {
                    $order->items()->create([
                        'presentation_id' => $item['presentation_id'],
                        'quantity_ordered' => $item['quantity_ordered'],
                        'unit_price' => $item['unit_price'],
                        'subtotal' => $item['subtotal'],
                    ]);
                }

                // Recalcular total
                $order->update([
                    'total_amount' => $order->items()->sum('subtotal'),
                ]);
            }

            return $order;
        });
    }

    public function getById(int $id)
    {
        return $this->purchaseOrderRepository->getById($id);
    }

    public function receiveOrder(int $id, array $data)
    {
        try {
            DB::beginTransaction();

            $order = $this->purchaseOrderRepository->getById($id);

            if ($order->status !== 'pending') {
                throw new \Exception('Solo se pueden recibir órdenes en estado pendiente');
            }

            $result = $this->receivePurchaseOrderAction->execute($order, $data);

            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function cancelOrder(int $id)
    {
        try {
            DB::beginTransaction();

            $order = $this->purchaseOrderRepository->getById($id);

            if ($order->status === 'cancelled') {
                throw new \Exception('La orden ya está cancelada');
            }

            $result = $this->cancelPurchaseOrderAction->execute($order);

            DB::commit();

            return $result;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
