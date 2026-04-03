<?php

namespace App\Modules\Administrator\Treasury\Services;

use App\Modules\Administrator\Treasury\Repositories\CashRegisterRepository;
use Illuminate\Http\Request;

class CashRegisterService
{
    public function __construct(
        private CashRegisterRepository $cashRegisterRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->cashRegisterRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->cashRegisterRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->cashRegisterRepository->delete($id);
    }

    public function getActiveCashRegisters(int $infrastructureId)
    {
        return $this->cashRegisterRepository->getActiveCashRegisters($infrastructureId);
    }
}
