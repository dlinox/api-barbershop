<?php

namespace App\Models\Academy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Common\Traits\HasDataTable;

class EnrollmentPayment extends Model
{
    use HasDataTable;

    protected $table = 'academy_enrollment_payments';

    protected $fillable = [
        'enrollment_id',
    ];

    protected $casts = [
        'enrollment_id' => 'integer',
    ];

    protected static $searchColumns = [
        'core_persons.name',
        'core_persons.paternal_surname',
        'core_persons.maternal_surname',
        'core_persons.document_number',
        'academy_groups.name'
    ];

    public function details(): HasMany
    {
        return $this->hasMany(EnrollmentPaymentDetail::class, 'enrollment_payment_id');
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }
}
