<?php

namespace App\Modules\Administrator\Treasury\Repositories;

use App\Models\Treasury\ExpenseType;

class ExpenseTypeRepository
{
    public function dataTable($request)
    {
        $query = ExpenseType::query();

        if (empty($request->sortBy)) {
            $query->orderBy('id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data): ExpenseType
    {
        return ExpenseType::updateOrCreate(['id' => $data['id'] ?? null], $data);
    }

    public function delete(int $id): void
    {
        $expenseType = ExpenseType::findOrFail($id);

        if ($expenseType->expenses()->exists()) {
            throw new \Exception('No se puede eliminar el tipo de gasto porque tiene gastos relacionados');
        }

        $expenseType->delete();
    }

    public function getActiveExpenseTypes()
    {
        return ExpenseType::where('is_active', true)->orderBy('name')->get();
    }
}
