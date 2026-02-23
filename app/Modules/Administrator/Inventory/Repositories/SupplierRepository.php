<?php

namespace App\Modules\Administrator\Inventory\Repositories;

use App\Models\Inventory\Supplier;

class SupplierRepository
{
    public function dataTable($request)
    {
        return Supplier::dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        return Supplier::updateOrCreate(['id' => $data['id']], $data);
    }

    public function delete(int $id)
    {
        $supplier = Supplier::findOrFail($id);

        if ($supplier->purchaseOrders()->count() > 0) {
            throw new \Exception('No se puede eliminar el proveedor porque tiene órdenes de compra relacionadas');
        }

        $supplier->delete();
        return $supplier;
    }

    public function getActiveSuppliers()
    {
        return Supplier::where('is_active', true)->get();
    }
}
