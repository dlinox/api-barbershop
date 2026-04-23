<?php

namespace App\Models\Academy;

use App\Common\Traits\HasDataTable;
use App\Common\Traits\HasInfrastructure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasDataTable, HasInfrastructure;

    protected $table = 'academy_branches';

    protected $fillable = [
        'name',
        'address',
        'logo',
        'ubication',
        'is_active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static $searchColumns = [
        'name',
        'address'
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'branch_id');
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class, 'branch_id');
    }
}
