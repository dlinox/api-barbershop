<?php

namespace App\Models\Barbershop;

use App\Common\Traits\HasDataTable;
use App\Common\Traits\HasInfrastructure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasDataTable, HasInfrastructure;

    protected $table = 'barbershop_branches';

    protected $fillable = [
        'name',
        'address',
        'ubication',
        'phone',
        'location_lat',
        'location_lng',
        'is_active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'location_lat' => 'decimal:8',
        'location_lng' => 'decimal:8',
    ];

    public static $searchColumns = [
        'name',
        'address',
    ];

    public function services(): HasMany
    {
        return $this->hasMany(Service::class, 'branch_id');
    }
}
