<?php

namespace App\Modules\Administrator\Treasury\Repositories;

use App\Models\Treasury\Expense;
use App\Common\Traits\HasInfrastructureScope;
use App\Modules\Administrator\Treasury\Repositories\Actions\SaveExpenseAction;
use Illuminate\Http\Request;

class ExpenseRepository
{
    use HasInfrastructureScope;

    public function __construct(
        private readonly SaveExpenseAction $saveExpenseAction,
    ) {}

    public function listByCashSession(int $cashSessionId)
    {
        return Expense::where('cash_session_id', $cashSessionId)
            ->with(['expenseType', 'paymentMethod'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function dataTable(Request $request)
    {
        $query = Expense::select('treasury_expenses.*')
            ->with(['expenseType', 'infrastructure', 'user', 'paymentMethod']);

        $this->scopeByInfrastructure($query, 'treasury_expenses.infrastructure_id');

        if (empty($request->sortBy)) {
            $query->orderBy('treasury_expenses.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function saveCashExpense(array $data): Expense
    {
        return $this->saveExpenseAction->execute($data, 'approved');
    }

    public function saveGeneralExpense(array $data): Expense
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

    public function getById(int $id)
    {
        return Expense::with(['expenseType', 'infrastructure', 'user', 'cashSession', 'paymentMethod'])
            ->findOrFail($id);
    }
}
