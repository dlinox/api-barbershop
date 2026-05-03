<?php

namespace App\Modules\Administrator\Treasury\Services;

use App\Modules\Administrator\Treasury\Repositories\ExpenseRepository;
use Illuminate\Http\Request;

class ExpenseService
{
    public function __construct(
        private readonly ExpenseRepository $repository,
    ) {}

    // ─── Gastos de Caja (CashSession) ───

    public function listByCashSession(int $cashSessionId)
    {
        return $this->repository->listByCashSession($cashSessionId);
    }

    public function saveCashExpense(array $data)
    {
        return $this->repository->saveCashExpense($data);
    }

    // ─── Gastos Generales ───

    public function dataTable(Request $request)
    {
        return $this->repository->dataTable($request);
    }

    public function saveGeneralExpense(array $data)
    {
        return $this->repository->saveGeneralExpense($data);
    }

    public function getById(int $id)
    {
        return $this->repository->getById($id);
    }

    public function approve(int $id)
    {
        return $this->repository->approve($id);
    }

    // ─── Compartido ───

    public function cancel(int $id)
    {
        return $this->repository->cancel($id);
    }
}
