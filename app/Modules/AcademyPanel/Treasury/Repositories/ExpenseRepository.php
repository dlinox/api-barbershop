<?php

namespace App\Modules\AcademyPanel\Treasury\Repositories;

use App\Models\Treasury\Expense;
use App\Common\Http\Context\AdminContext;
use App\Modules\Administrator\Treasury\Repositories\Actions\SaveExpenseAction;
use Illuminate\Http\Request;

class ExpenseRepository
{
    public function __construct(
        private readonly SaveExpenseAction $saveExpenseAction,
    ) {}

    public function dataTable(Request $request)
    {
        $infrastructureId = AdminContext::infrastructureId();

        $query = Expense::select('treasury_expenses.*')
            ->with(['expenseType', 'infrastructure', 'user', 'paymentMethod'])
            ->where('treasury_expenses.infrastructure_id', $infrastructureId);

        if (empty($request->sortBy)) {
            $query->orderBy('treasury_expenses.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function save(array $data): Expense
    {
        return $this->saveExpenseAction->execute($data, 'pending');
    }

    public function approve(int $id): Expense
    {
        $expense = Expense::findOrFail($id);
        $expense->update(['status' => 'approved']);
        return $expense;
    }

    public function cancel(int $id): Expense
    {
        $expense = Expense::findOrFail($id);
        $expense->update(['status' => 'cancelled']);
        return $expense;
    }

    public function getById(int $id): Expense
    {
        return Expense::with(['expenseType', 'infrastructure', 'user', 'cashSession', 'paymentMethod'])
            ->findOrFail($id);
    }
}
