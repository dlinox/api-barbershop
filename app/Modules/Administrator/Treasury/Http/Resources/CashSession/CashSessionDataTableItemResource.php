<?php

namespace App\Modules\Administrator\Treasury\Http\Resources\CashSession;

use Illuminate\Http\Resources\Json\JsonResource;

class CashSessionDataTableItemResource extends JsonResource
{
    public function toArray($request): array
    {

        $expenses = $this->expenses->sum('amount');
        $incomes = $this->incomes->where('status', 'completed')->sum('total');

        return [
            'id'                    => $this->id,
            'cashRegisterId'        => $this->cash_register_id,
            'openedBy'              => $this->openedByUser?->username ?? '-',
            'closedBy'              => $this->closedByUser?->username ?? null,
            'openingAmount'         => (float) $this->opening_amount,
            'expenses'              => (float) $expenses,
            'incomes'               => (float) $incomes,
            'expectedClosingAmount' => (float) $this->opening_amount +  $incomes - $expenses,
            'actualClosingAmount'   => $this->actual_closing_amount !== null ? (float) $this->actual_closing_amount : null,
            'difference'            => $this->difference !== null ? (float) $this->difference : null,
            'status'                => $this->status,
            'openedAt'              => $this->opened_at?->format('d/m/Y H:i'),
            'closedAt'              => $this->closed_at?->format('d/m/Y H:i'),
            'notes'                 => $this->notes,
        ];
    }
}
