<?php

namespace App\Modules\Administrator\Treasury\Repositories\Queries;

use App\Models\Treasury\Income;

class IncomeDetailQuery
{
    public function __invoke(int $id): Income
    {
        return Income::with([
            'details',
            'paymentMethods.paymentMethod',
            'person',
            'infrastructure',
        ])->findOrFail($id);
    }

    public function toBladeData(Income $income): array
    {
        $person = $income->person;

        $statusMap = [
            'completed' => 'Completado',
            'cancelled' => 'Anulado',
        ];

        return [
            'receipt_serie'       => $income->receipt_serie,
            'receipt_number'      => $income->receipt_number,
            'receipt_full_number' => $income->receipt_serie . '-' . str_pad($income->receipt_number, 8, '0', STR_PAD_LEFT),
            'transaction_date'    => $income->transaction_date?->format('d/m/Y'),
            'status'              => $statusMap[$income->status] ?? $income->status,
            'observations'        => $income->observations,

            'client_name'     => $person ? trim("{$person->name} {$person->paternal_surname} {$person->maternal_surname}") : '—',
            'client_document' => $person?->document_number ?? '—',

            'details' => $income->details->map(fn($d) => [
                'description' => $d->description,
                'quantity'    => $d->quantity,
                'unit_price'  => $d->unit_price,
                'discount'    => $d->discount,
                'subtotal'    => $d->subtotal,
            ])->toArray(),

            'payment_methods' => $income->paymentMethods->map(fn($pm) => [
                'method'    => $pm->paymentMethod?->name ?? '—',
                'amount'    => $pm->amount,
                'reference' => $pm->payment_reference,
            ])->toArray(),

            'subtotal' => $income->subtotal,
            'discount' => $income->discount,
            'tax'      => $income->tax,
            'total'    => $income->total,
        ];
    }
}
