<?php

namespace App\Modules\Auth\Repositories;

use App\Models\Auth\Session;
use Illuminate\Support\Str;

class SessionRepository
{
    /**
     * Create a new session for user
     */
    public function create(int $userId, ?int $profileId, ?string $ipAddress, ?string $userAgent): Session
    {
        return Session::create([
            'auth_user_id' => $userId,
            'behavior_profile_id' => $profileId,
            'session_token' => Str::random(64),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'last_used_at' => now(),
            'expires_at' => now()->addDays(7),
        ]);
    }

    /**
     * Update profile in session
     */
    public function updateProfile(int $userId, int $profileId): void
    {
        Session::where('auth_user_id', $userId)
            ->update(['behavior_profile_id' => $profileId]);
    }

    /**
     * Invalidate all sessions for user
     */
    public function invalidateAllForUser(int $userId): void
    {
        Session::where('auth_user_id', $userId)->delete();
    }
}
