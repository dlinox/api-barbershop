<?php

namespace App\Common\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use App\Models\Behavior\Profile;
use App\Common\Exceptions\PermissionDeniedException;

class CheckSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Use cached value if CheckPermission already ran
        if ($request->attributes->has('is_super_admin')) {
            if (!$request->attributes->get('is_super_admin')) {
                throw new PermissionDeniedException('super_admin');
            }
            return $next($request);
        }

        $isSuperAdmin = false;

        try {
            $payload = JWTAuth::parseToken()->getPayload();
            $profileId = $payload->get('prf');

            if ($profileId) {
                $profile = Profile::with('role')
                    ->where('id', $profileId)
                    ->where('is_active', true)
                    ->first();

                if ($profile?->role && $profile->role->name === 'super_admin' && (int) $profile->role->level === 0) {
                    $isSuperAdmin = true;
                }
            }
        } catch (\Exception) {
            // Token inválido o ausente — el middleware auth:api ya habrá rechazado antes
        }

        // Cache for subsequent middlewares in the same request
        $request->attributes->set('is_super_admin', $isSuperAdmin);
        $request->attributes->set('user_permissions', []);

        if (!$isSuperAdmin) {
            throw new PermissionDeniedException('super_admin');
        }

        return $next($request);
    }
}
