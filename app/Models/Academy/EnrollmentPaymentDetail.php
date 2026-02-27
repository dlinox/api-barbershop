<?php

namespace App\Models\Academy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnrollmentPaymentDetail extends Model
{
    protected $table = 'academy_enrollment_payment_details';

    protected $fillable = [
        'enrollment_payment_id',
        'group_payment_plan_id',
        'type',
        'subtotal',
        'discount',
        'total',
    ];

    protected $casts = [
        'group_payment_plan_id' => 'integer',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function enrollmentPayment(): BelongsTo
    {
        return $this->belongsTo(EnrollmentPayment::class, 'enrollment_payment_id');
    }

    public function paymentPlan(): BelongsTo
    {
        return $this->belongsTo(GroupPaymentPlan::class, 'group_payment_plan_id');
    }
}
