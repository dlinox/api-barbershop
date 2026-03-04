<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use App\Models\Core\Person;
use App\Models\Behavior\Profile;
use App\Common\Traits\HasDataTable;

class Client extends Model
{
    use HasDataTable;

    protected $table = 'profile_clients';
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = [
        'id',
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
}
