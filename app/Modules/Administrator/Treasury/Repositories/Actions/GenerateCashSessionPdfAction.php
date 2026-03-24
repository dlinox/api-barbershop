<?php

namespace App\Modules\Administrator\Treasury\Repositories\Actions;

use App\Models\Treasury\CashSession;
use App\Models\Core\Company;
use App\Common\Helpers\PdfHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class GenerateCashSessionPdfAction
{
    public function execute(CashSession $session): string
    {
        $session->load([
            'cashRegister',
            'openedByUser',
            'closedByUser',
            'incomes' => fn($q) => $q->with(['person', 'details', 'paymentMethods.paymentMethod']),
            'expenses' => fn($q) => $q->where('status', 'approved')->with(['expenseType', 'paymentMethod']),
        ]);

        $completedIncomes = $session->incomes->where('status', 'completed');
        $totalIncomes = $completedIncomes->sum('total');
        $totalExpenses = $session->expenses->sum('amount');
        $expectedClosingAmount = (float) $session->opening_amount + (float) $totalIncomes - (float) $totalExpenses;

        $data = array_merge($this->baseData(), [
            'cash_register_name' => $session->cashRegister?->name ?? 'Caja',
            'opened_at'          => $session->opened_at?->format('d/m/Y H:i'),
            'closed_at'          => $session->closed_at?->format('d/m/Y H:i'),
            'opened_by'          => $session->openedByUser?->username ?? '-',
            'closed_by'          => $session->closedByUser?->username ?? '-',

            'opening_amount'          => (float) $session->opening_amount,
            'total_incomes'           => (float) $totalIncomes,
            'total_expenses'          => (float) $totalExpenses,
            'expected_closing_amount' => $expectedClosingAmount,
            'actual_closing_amount'   => (float) $session->actual_closing_amount,
            'difference'              => (float) $session->actual_closing_amount - $expectedClosingAmount,

            'incomes_count'  => $completedIncomes->count(),
            'expenses_count' => $session->expenses->count(),

            'incomes' => $session->incomes->map(fn($income) => [
                'receipt_number' => $income->receipt_serie . '-' . str_pad($income->receipt_number, 8, '0', STR_PAD_LEFT),
                'client'         => $income->person?->full_name ?? 'Cliente general',
                'date'           => $income->transaction_date?->format('d/m/Y'),
                'status'         => $income->status,
                'discount'       => (float) $income->discount,
                'total'          => (float) $income->total,
            ])->toArray(),

            'expenses' => $session->expenses->map(fn($expense) => [
                'type'           => $expense->expenseType?->name ?? '-',
                'description'    => $expense->description ?? '-',
                'payment_method' => $expense->paymentMethod?->name ?? '-',
                'date'           => $expense->transaction_date?->format('d/m/Y'),
                'amount'         => (float) $expense->amount,
            ])->toArray(),

            'payment_method_summary' => $this->buildPaymentMethodSummary($completedIncomes),

            'notes' => $session->notes,
        ]);

        $html = View::make('cash-sessions.summary', $data)->render();

        return PdfHelper::createFromHtml($html)->Output('', 'S');
    }

    private function buildPaymentMethodSummary($incomes): array
    {
        $summary = [];

        foreach ($incomes as $income) {
            foreach ($income->paymentMethods as $pm) {
                $methodName = $pm->paymentMethod?->name ?? 'Sin definir';

                if (!isset($summary[$methodName])) {
                    $summary[$methodName] = ['method' => $methodName, 'count' => 0, 'total' => 0];
                }

                $summary[$methodName]['count']++;
                $summary[$methodName]['total'] += (float) $pm->amount;
            }
        }

        return array_values($summary);
    }

    private function baseData(): array
    {
        return [
            'company'      => Company::first(),
            'generated_by' => Auth::user()?->username ?? 'Sistema',
            'generated_at' => now()->format('d/m/Y H:i'),
        ];
    }
}
