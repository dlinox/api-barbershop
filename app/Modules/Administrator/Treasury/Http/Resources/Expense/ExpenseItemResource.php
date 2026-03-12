<?php

namespace App\Modules\Administrator\Treasury\Http\Resources\Expense;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'cashSessionId' => $this->cash_session_id,
            'userId' => $this->user_id,
            'amount' => $this->amount,
            'description' => $this->description,
            'createdAt' => $this->created_at,
        ];
    }
}
