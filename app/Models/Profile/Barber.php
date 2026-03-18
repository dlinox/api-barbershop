<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Core\Person;
use App\Models\Behavior\Profile;
use App\Common\Traits\HasDataTable;

class Barber extends Model
{
    use HasDataTable;

    protected $table = 'profile_barbers';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'branch_id', //barbershop_branches
        'commission_percentage',
        'is_active',
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
            ->where('employee_type', 'barber');
    }

    public function advances(): MorphMany
    {
        return $this->morphMany(\App\Models\Treasury\EmployeeAdvance::class, 'employee', 'employee_type', 'employee_id')
            ->where('employee_type', 'barber');
    }
}
