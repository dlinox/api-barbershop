<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Behavior\Profile;

class Session extends Model
{
    protected $table = 'auth_sessions';

    protected $fillable = [
        'auth_user_id',
        'behavior_profile_id',
        'session_token',
        'ip_address',
        'user_agent',
        'last_used_at',
        'expires_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auth_user_id');
    }

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'behavior_profile_id');
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
