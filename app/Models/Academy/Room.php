<?php

namespace App\Models\Academy;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    use HasDataTable;
    protected $table = 'academy_rooms';

    protected $fillable = [
        'branch_id',
        'number',
        'description',
        'capacity',
        'floor',
        'is_active',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'number' => 'integer',
        'capacity' => 'integer',
        'floor' => 'integer',
    ];

    public static $searchColumns = [
        'number',
        'capacity',
        'floor',
        'academy_branches.name',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function groups()
    {
        return $this->hasMany(Group::class, 'room_id');
    }
}
