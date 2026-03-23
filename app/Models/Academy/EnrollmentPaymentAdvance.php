<?php

namespace App\Models\Academy;

use App\Models\Profile\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnrollmentPaymentAdvance extends Model
{
    protected $table = 'academy_enrollment_payment_advances';

    protected $fillable = [
        'student_id',
        'enrollment_payment_id',
        'amount',
        'observation',
        'payment_date',
        'used_at',
    ];

    protected $casts = [
        'student_id' => 'integer',
        'enrollment_payment_id' => 'integer',
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'used_at' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'core_person_id');
    }

    public function enrollmentPayment(): BelongsTo
    {
        return $this->belongsTo(EnrollmentPayment::class, 'enrollment_payment_id');
    }
}
