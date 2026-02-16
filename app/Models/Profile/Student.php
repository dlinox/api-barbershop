<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Core\Person;
use App\Models\Behavior\Profile;
use App\Common\Traits\HasDataTable;
use App\Models\Academy\Enrollment;

class Student extends Model
{
    use HasDataTable;

    protected $table = 'profile_students';
    protected $primaryKey = 'core_person_id';
    public $incrementing = false;

    protected $fillable = [
        'core_person_id',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'core_person_id');
    }

    public function profile(): MorphOne
    {
        return $this->morphOne(Profile::class, 'profileable');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'profile_student_id');
    }
}
