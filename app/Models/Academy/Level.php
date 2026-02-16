<?php

namespace App\Models\Academy;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasDataTable;

    protected $table = 'academy_levels';

    protected $fillable = [
        'order',
        'name',
        'description',
        'duration_months',
        'is_active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'duration_months' => 'integer',
        'order' => 'integer',
    ];

    protected static $searchColumns = [
        'academy_levels.name',
        'academy_levels.description',
    ];

    public function groups()
    {
        return $this->hasMany(Group::class, 'level_id');
    }
}
