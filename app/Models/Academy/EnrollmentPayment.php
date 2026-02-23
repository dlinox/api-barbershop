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

    protected static $searchColumns = [
        'core_persons.name',
        'core_persons.paternal_surname',
        'core_persons.maternal_surname',
        'core_persons.document_number',
        'academy_groups.name'
    ];
}
