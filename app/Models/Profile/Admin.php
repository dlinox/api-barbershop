<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Core\Person;
use App\Models\Profile\AdminInfrastructure;
use App\Models\Behavior\Profile;
use App\Common\Traits\HasDataTable;

class Admin extends Model
{
    use HasDataTable;
    protected $table = 'profile_admins';
    protected $primaryKey = 'core_person_id';
    public $incrementing = false;

    protected $fillable = [
        'core_person_id',
    ];

    public static $searchColumns = [
        'core_persons.name',
        'core_persons.paternal_surname',
        'core_persons.maternal_surname',
        'core_persons.document_number',
        'auth_users.username',
        'auth_users.email',
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'core_person_id');
    }

    public function profile(): MorphOne
    {
        return $this->morphOne(Profile::class, 'profileable');
    }

    public function infrastructures()
    {
        return $this->belongsToMany(
            \App\Models\Core\Infrastructure::class, 
            'profile_admin_infrastructures', 
            'profile_admin_id', 
            'core_infrastructure_id', 
            'core_person_id', 
            'id'
        );
    }
}
