<?php

namespace App\Models\Academy;

use Illuminate\Database\Eloquent\Model;
use App\Common\Traits\HasDataTable;
use App\Models\Profile\Student;
use App\Models\Core\File;
use App\Models\Academy\EnrollmentGroupChange;

class Enrollment extends Model
{
    use HasDataTable;

    protected $table = 'academy_enrollments';

    protected $fillable = [
        'profile_student_id',
        'group_id',
        'status',
        'date',
    ];

    protected $casts = [
        'profile_student_id' => 'integer',
        'group_id' => 'integer',
    ];


    public static $searchColumns = [
        'core_persons.name',
        'core_persons.paternal_surname',
        'core_persons.maternal_surname',
        'core_persons.document_number',
        'core_persons.phone',

        'academy_groups.name',
    ];

    public function payments()
    {
        return $this->hasMany(EnrollmentPayment::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function materials()
    {
        return $this->belongsToMany(Material::class, 'academy_enrollment_materials')->withPivot('quantity');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'profile_student_id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function groupChangeAsOrigin()
    {
        return $this->hasOne(EnrollmentGroupChange::class, 'origin_enrollment_id');
    }

    public function groupChangeAsDestination()
    {
        return $this->hasOne(EnrollmentGroupChange::class, 'destination_enrollment_id');
    }
}
