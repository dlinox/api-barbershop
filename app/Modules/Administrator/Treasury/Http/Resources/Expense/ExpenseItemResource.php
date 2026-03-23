<?php

namespace App\Modules\Administrator\Treasury\Http\Resources\Expense;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'              => $this->id,
            'cashSessionId'   => $this->cash_session_id,
            'expenseTypeId'   => $this->expense_type_id,
            'expenseType'     => $this->whenLoaded('expenseType', fn () => $this->expenseType->name),
            'paymentMethodId' => $this->payment_method_id,
            'paymentMethod'   => $this->whenLoaded('paymentMethod', fn () => $this->paymentMethod->name),
            'userId'          => $this->user_id,
            'amount'          => $this->amount,
            'description'     => $this->description,
            'status'          => $this->status,
            'createdAt'       => $this->created_at,
        ];
    }
}
