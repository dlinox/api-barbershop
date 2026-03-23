<?php

namespace App\Modules\Administrator\Treasury\Http\Resources\Expense;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ExpenseDataTableItemResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                    => $this->id,
            'expenseTypeId'         => $this->expense_type_id,
            'expenseType'           => $this->whenLoaded('expenseType', fn () => $this->expenseType->name),
            'infrastructureId'      => $this->infrastructure_id,
            'infrastructure'        => $this->whenLoaded('infrastructure', fn () => $this->infrastructure->name),
            'cashSessionId'         => $this->cash_session_id,
            'paymentMethodId'       => $this->payment_method_id,
            'paymentMethod'         => $this->whenLoaded('paymentMethod', fn () => $this->paymentMethod->name),
            'userId'                => $this->user_id,
            'userName'              => $this->whenLoaded('user', fn () => $this->user->username),
            'amount'                => $this->amount,
            'description'           => $this->description,
            'transactionDate'       => $this->transaction_date?->format('Y-m-d'),
            'voucherDate'           => $this->voucher_date?->format('Y-m-d'),
            'voucherNumber'         => $this->voucher_number,
            'voucherImagePath'      => $this->voucher_image_path
                ? Storage::disk('public')->url($this->voucher_image_path)
                : null,
            'status'                => $this->status,
            'createdAt'             => $this->created_at,
        ];
    }
}
