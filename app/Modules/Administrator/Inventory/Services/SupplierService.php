<?php

namespace App\Modules\Administrator\Inventory\Services;

use App\Modules\Administrator\Inventory\Repositories\SupplierRepository;
use Illuminate\Http\Request;

class SupplierService
{
    public function __construct(
        private SupplierRepository $supplierRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->supplierRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->supplierRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->supplierRepository->delete($id);
    }

    public function getActiveSuppliers()
    {
        return $this->supplierRepository->getActiveSuppliers();
    }
}
