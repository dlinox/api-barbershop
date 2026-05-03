<?php

namespace App\Modules\Administrator\Treasury\Http\Resources\Income;

use Illuminate\Http\Resources\Json\JsonResource;

class IncomeAuditResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                     => $this->id,
            'editedBy'               => $this->edited_by,
            'editDescription'        => $this->edit_description,
            'subtotalBefore'         => (float) $this->subtotal_before,
            'discountBefore'         => (float) $this->discount_before,
            'totalBefore'            => (float) $this->total_before,
            'detailsSnapshot'        => $this->details_snapshot,
            'paymentMethodsSnapshot' => $this->payment_methods_snapshot,
            'createdAt'              => $this->created_at,
        ];
    }
}
