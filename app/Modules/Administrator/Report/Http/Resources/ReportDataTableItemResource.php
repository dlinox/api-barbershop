<?php

namespace App\Modules\Administrator\Report\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportDataTableItemResource extends JsonResource
{
    public function toArray($request): array
    {
        $data = $this->data ?? [];

        return [
            'id'                 => $this->id,
            'name'               => $this->name,
            'reference'          => $this->reference,
            'version'            => $this->version,
            'type'               => $this->type,
            'branchName'         => $data['branch_name'] ?? null,
            'workerName'         => $data['worker_name'] ?? null,
            'date'               => $data['date'] ?? null,
            'totalCash'          => $data['total_cash'] ?? 0,
            'totalBank'          => $data['total_bank'] ?? 0,
            'totalDay'           => $data['total_day'] ?? 0,
            'rowsCount'          => $data['rows_count'] ?? 0,
            'groupName'          => $data['group_name'] ?? null,
            'month'              => $data['month'] ?? null,
            'year'               => $data['year'] ?? null,
            'studentCount'       => $data['student_count'] ?? null,
            'attendanceRate'     => $data['attendance_rate'] ?? null,
            // Barbershop fields
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
            // Treasury fields
            'total'              => $data['total'] ?? null,
            'totalIncome'        => $data['total_income'] ?? null,
            'totalExpense'       => $data['total_expense'] ?? null,
            'netProfit'          => $data['net_profit'] ?? null,
            'registerName'       => $data['register_name'] ?? null,
            'cashSessionId'      => $data['cash_session_id'] ?? null,
            // Inventory fields
            'productName'        => $data['product_name'] ?? null,
            'sku'                => $data['sku'] ?? null,
            'movementsCount'     => $data['movements_count'] ?? null,
            'finalBalance'       => $data['final_balance'] ?? null,
            'productsCount'      => $data['products_count'] ?? null,
            'totalValue'         => $data['total_value'] ?? null,
            'totalDeficit'       => $data['total_deficit'] ?? null,
            'totalRestockCost'   => $data['total_restock_cost'] ?? null,
            'generatedBy'        => $this->whenLoaded('generatedBy', fn() => $this->generatedBy->username),
            'createdAt'          => $this->created_at?->format('Y-m-d H:i'),
        ];
    }
}
