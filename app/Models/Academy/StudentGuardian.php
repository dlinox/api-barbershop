<?php

namespace App\Models\Academy;

use App\Common\Traits\HasDataTable;
use App\Models\Profile\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentGuardian extends Model
{
    use HasDataTable;

    protected $table = 'academy_student_guardians';

    protected $fillable = [
        'student_id',
        'full_name',
        'kinship',
        'phone',
    ];

    public static $searchColumns = [
        'full_name',
        'kinship',
        'phone',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'core_person_id');
    }
}
