<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Core\Person;
use App\Models\Behavior\Profile;
use App\Models\Academy\GroupTeacher;
use App\Models\Academy\Branch;
use App\Common\Traits\HasDataTable;

class Teacher extends Model
{
    use HasDataTable;

    protected $table = 'profile_teachers';
    protected $primaryKey = 'core_person_id';
    public $incrementing = false;

    protected $fillable = [
        'core_person_id',
        'branch_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'core_person_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function profile(): MorphOne
    {
        return $this->morphOne(Profile::class, 'profileable');
    }

    public function groupTeachers(): HasMany
    {
        return $this->hasMany(GroupTeacher::class, 'teacher_id', 'core_person_id');
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(\App\Models\Treasury\EmployeePayment::class, 'employee', 'employee_type', 'employee_id')
            ->where('employee_type', 'teacher');
    }

    public function advances(): MorphMany
    {
        return $this->morphMany(\App\Models\Treasury\EmployeeAdvance::class, 'employee', 'employee_type', 'employee_id')
            ->where('employee_type', 'teacher');
    }
}
