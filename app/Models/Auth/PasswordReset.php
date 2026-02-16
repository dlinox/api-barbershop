<?php

namespace App\Models\Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordReset extends Model
{
    protected $table = 'auth_password_resets';

    protected $fillable = [
        'auth_user_id',
        'reset_token',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auth_user_id');
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }
}
