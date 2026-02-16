<?php

namespace App\Models\Academy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Common\Traits\HasDataTable;

class EnrollmentPayment extends Model
{
    use HasDataTable;

    protected $table = 'academy_enrollment_payments';

    protected $fillable = [
        'enrollment_id',
        'group_payment_plan_id',
        'type',
        'subtotal',
        'discount',
        'total',
    ];

    protected $casts = [
        'enrollment_id' => 'integer',
        'group_payment_plan_id' => 'integer',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }
}
