<?php

namespace App\Modules\Administrator\Treasury\Repositories;

use App\Models\Treasury\Expense;

class ExpenseRepository
{
    public function listByCashSession(int $cashSessionId)
    {
        return Expense::where('cash_session_id', $cashSessionId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function createOrUpdate(array $data)
    {
        return Expense::updateOrCreate(['id' => $data['id']], $data);
    }

    public function delete(int $id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();
        return $expense;
    }
}
