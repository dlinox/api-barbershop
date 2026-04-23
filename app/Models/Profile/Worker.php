<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Core\Person;
use App\Models\Behavior\Profile;
use App\Common\Traits\HasDataTable;

class Worker extends Model
{
    use HasDataTable;

    protected $table = 'profile_workers';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'infrastructure_id',
        'position',
        'monthly_salary',
        'payment_frequency',
        'is_active',
    ];

    protected $casts = [
        'position' => 'string',
        'monthly_salary' => 'decimal:2',
        'payment_frequency' => 'string',
        'is_active' => 'boolean',
    ];

    public static $searchColumns = [
        'core_persons.name',
        'core_persons.paternal_surname',
        'core_persons.maternal_surname',
        'core_persons.document_number',
        'core_persons.phone',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'id');
    }

    public function profile(): MorphOne
    {
        return $this->morphOne(Profile::class, 'profileable');
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(\App\Models\Treasury\EmployeePayment::class, 'employee', 'employee_type', 'employee_id')
            ->where('employee_type', 'worker');
    }

    public function advances(): MorphMany
    {
        return $this->morphMany(\App\Models\Treasury\EmployeeAdvance::class, 'employee', 'employee_type', 'employee_id')
            ->where('employee_type', 'worker');
    }
}
