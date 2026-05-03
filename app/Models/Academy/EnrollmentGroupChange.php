<?php

namespace App\Models\Academy;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnrollmentGroupChange extends Model
{
    protected $table = 'academy_enrollment_group_changes';

    protected $fillable = [
        'origin_enrollment_id',
        'destination_enrollment_id',
        'reason',
        'changed_by_user_id',
        'changed_at',
    ];

    protected $casts = [
        'origin_enrollment_id'      => 'integer',
        'destination_enrollment_id' => 'integer',
        'changed_by_user_id'        => 'integer',
        'changed_at'                => 'datetime',
    ];

    public function originEnrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class, 'origin_enrollment_id');
    }

    public function destinationEnrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class, 'destination_enrollment_id');
    }

    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }
}
