<?php

namespace App\Models\Academy;

use Illuminate\Database\Eloquent\Model;
use App\Common\Traits\HasDataTable;

class Enrollment extends Model
{
    use HasDataTable;

    protected $table = 'academy_enrollments';

    protected $fillable = [
        'profile_student_id',
        'group_id',
        'status',
    ];

    protected $casts = [
        'profile_student_id' => 'integer',
        'group_id' => 'integer',
    ];

    public function payments()
    {
        return $this->hasMany(EnrollmentPayment::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
