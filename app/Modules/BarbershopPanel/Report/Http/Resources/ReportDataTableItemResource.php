<?php

namespace App\Modules\BarbershopPanel\Report\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportDataTableItemResource extends JsonResource
{
    public function toArray($request): array
    {
        $data = $this->data ?? [];

        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'version'            => $this->version,
            'type'               => $this->type,
            'branchName'         => $data['branch_name'] ?? null,
            'date'               => $data['date'] ?? null,
            'totalCash'          => $data['total_cash'] ?? 0,
            'totalBank'          => $data['total_bank'] ?? 0,
            'totalDay'           => $data['total_day'] ?? 0,
            'rowsCount'          => $data['rows_count'] ?? 0,
            'dateFrom'           => $data['date_from'] ?? null,
            'dateTo'             => $data['date_to'] ?? null,
            'barberCount'        => $data['barber_count'] ?? null,
            'totalServices'      => $data['total_services'] ?? null,
            'totalCommission'    => $data['total_commission'] ?? null,
            'infrastructureName' => $data['infrastructure_name'] ?? null,
            'cashRegisterName'   => $data['cash_register_name'] ?? null,
            'openingAmount'      => $data['opening_amount'] ?? null,
            'totalIncomes'       => $data['total_incomes'] ?? null,
            'totalExpenses'      => $data['total_expenses'] ?? null,
            'expectedClosing'    => $data['expected_closing'] ?? null,
            'actualClosing'      => $data['actual_closing'] ?? null,
            'difference'         => $data['difference'] ?? null,
            'generatedBy'        => $this->whenLoaded('generatedBy', fn() => $this->generatedBy->username),
            'createdAt'          => $this->created_at?->format('Y-m-d H:i'),
        ];
    }
}
