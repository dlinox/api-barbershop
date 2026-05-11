<?php

namespace App\Models\Core;

use App\Models\Profile\Admin;
use App\Models\Profile\Barber;
use App\Models\Profile\Client;
use App\Models\Profile\Student;
use App\Models\Profile\Teacher;
use App\Models\Profile\Worker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Person extends Model
{
    protected $table = 'core_persons';

    protected $fillable = [
        'document_type',
        'document_number',
        'name',
        'paternal_surname',
        'maternal_surname',
        'date_birth',
        'phone',
        'email',
        'gender',
        'address',
        'city',
        'country',
    ];

    protected $casts = [
        'date_birth' => 'date',
    ];

    public function documentTypeRelation(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'document_type', 'code');
    }

    public function genderRelation(): BelongsTo
    {
        return $this->belongsTo(Gender::class, 'gender', 'code');
    }

    public function cityRelation(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city', 'code');
    }

    public function countryRelation(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country', 'code');
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->name} {$this->paternal_surname} {$this->maternal_surname}");
    }

    public function adminRelation(): HasOne
    {
        return $this->hasOne(Admin::class, 'core_person_id', 'id');
    }

    public function studentRelation(): HasOne
    {
        return $this->hasOne(Student::class, 'core_person_id', 'id');
    }

    public function teacherRelation(): HasOne
    {
        return $this->hasOne(Teacher::class, 'core_person_id', 'id');
    }

    public function clientRelation(): HasOne
    {
        return $this->hasOne(Client::class, 'id', 'id');
    }

    public function workerRelation(): HasOne
    {
        return $this->hasOne(Worker::class, 'id', 'id');
    }

    public function barberRelation(): HasOne
    {
        return $this->hasOne(Barber::class, 'id', 'id');
    }
}
