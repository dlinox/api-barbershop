<?php

namespace App\Models\Behavior;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\Auth\User;
use App\Models\Core\Person;

class Profile extends Model
{
    protected $table = 'behavior_profiles';

    protected $fillable = [
        'auth_user_id',
        'profileable_type',
        'profileable_id',
        'behavior_role_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auth_user_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'behavior_role_id');
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'profileable_id');
    }

    public function profileable(): MorphTo
    {
        return $this->morphTo();
    }
}
