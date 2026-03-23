<?php

namespace App\Modules\Administrator\Treasury\Services;

use App\Modules\Administrator\Treasury\Repositories\ExpenseTypeRepository;
use Illuminate\Http\Request;

class ExpenseTypeService
{
    public function __construct(
        private ExpenseTypeRepository $expenseTypeRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->expenseTypeRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->expenseTypeRepository->createOrUpdate($data);
    }

    public function delete(int $id): void
    {
        $this->expenseTypeRepository->delete($id);
    }

    public function getActiveExpenseTypes()
    {
        return $this->expenseTypeRepository->getActiveExpenseTypes();
    }
}
