<?php

namespace App\Models\Auth;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Behavior\Profile;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{

    use HasApiTokens;
    protected $table = 'auth_users';

    protected $fillable = [
        'username',
        'password',
        'email',
        'is_active',
        'email_verified_at',
        'last_sign_in_at',
    ];

    protected $hidden = [
        'password',
        'last_sign_in_at',
        'created_at',
        'updated_at',
        'email_verified_at',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_active' => 'boolean',
        'email_verified_at' => 'datetime',
        'last_sign_in_at' => 'datetime',
    ];

    // JWT Methods
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    // Relationships
    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class, 'auth_user_id');
    }

    public function passwordResets(): HasMany
    {
        return $this->hasMany(PasswordReset::class, 'auth_user_id');
    }

    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class, 'auth_user_id');
    }
}
