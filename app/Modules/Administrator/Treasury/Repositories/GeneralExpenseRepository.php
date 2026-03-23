<?php

namespace App\Modules\Administrator\Treasury\Repositories;

use App\Common\Traits\HasInfrastructureScope;
use App\Models\Treasury\Expense;

class GeneralExpenseRepository
{
    use HasInfrastructureScope;

    public function dataTable($request)
    {
        $query = Expense::select('treasury_expenses.*')
            ->with(['expenseType', 'infrastructure', 'user', 'paymentMethod']);

        $this->scopeByInfrastructure($query, 'treasury_expenses.infrastructure_id');

        if (empty($request->sortBy)) {
            $query->orderBy('treasury_expenses.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function getById(int $id)
    {
        return Expense::with(['expenseType', 'infrastructure', 'user', 'cashSession', 'paymentMethod'])
            ->findOrFail($id);
    }

    public function delete(int $id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();
        return $expense;
    }
}
