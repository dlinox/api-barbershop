<?php

namespace App\Common\Traits;

use App\Models\Behavior\Profile;
use App\Models\Core\Infrastructure;
use App\Models\Profile\AdminInfrastructure;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

trait HasInfrastructureScope
{
    protected function isSuperAdmin(): bool
    {
        $request = request();

        if ($request->attributes->has('is_super_admin')) {
            return $request->attributes->get('is_super_admin');
        }

        $isSuperAdmin = false;

        try {
            $payload = JWTAuth::parseToken()->getPayload();
            $profileId = $payload->get('prf');

            if ($profileId) {
                $profile = Profile::with('role')->find($profileId);

                if ($profile?->role) {
                    $isSuperAdmin = $profile->role->name === 'super_admin' && (int) $profile->role->level === 0;
                }
            }
        } catch (\Exception) {
            //
        }

        $request->attributes->set('is_super_admin', $isSuperAdmin);

        return $isSuperAdmin;
    }

    protected function getAdminInfrastructureIds(): ?array
    {
        if ($this->isSuperAdmin()) {
            return null;
        }

        $request = request();

        if ($request->attributes->has('admin_infrastructure_ids')) {
            return $request->attributes->get('admin_infrastructure_ids');
        }

        $infraIds = [];

        try {
            $payload = JWTAuth::parseToken()->getPayload();
            $profileId = $payload->get('prf');

            if ($profileId) {
                $profile = Profile::find($profileId);

                if ($profile && $profile->profileable_type === 'profile_admins') {
                    $infraIds = AdminInfrastructure::where('profile_admin_id', $profile->profileable_id)
                        ->pluck('core_infrastructure_id')
                        ->toArray();
                }
            }
        } catch (\Exception) {
            // Si no se puede parsear el token, retornar array vacío (sin acceso)
        }

        $request->attributes->set('admin_infrastructure_ids', $infraIds);

        return $infraIds;
    }

    protected function getAdminBranchIds(): ?array
    {
        return $this->getAdminBranchIdsByType('barbershop_branches', 'admin_branch_ids');
    }

    protected function getAdminAcademyBranchIds(): ?array
    {
        return $this->getAdminBranchIdsByType('academy_branches', 'admin_academy_branch_ids');
    }

    private function getAdminBranchIdsByType(string $infrastructurableType, string $cacheKey): ?array
    {
        $infraIds = $this->getAdminInfrastructureIds();

        if ($infraIds === null) {
            return null;
        }

        $request = request();

        if ($request->attributes->has($cacheKey)) {
            return $request->attributes->get($cacheKey);
        }

        $branchIds = Infrastructure::whereIn('id', $infraIds)
            ->where('infrastructurable_type', $infrastructurableType)
            ->pluck('infrastructurable_id')
            ->toArray();

        $request->attributes->set($cacheKey, $branchIds);

        return $branchIds;
    }

    protected function scopeByBranch($query, string $branchColumn = 'branch_id'): void
    {
        $branchIds = $this->getAdminBranchIds();

        if ($branchIds !== null) {
            $query->whereIn($branchColumn, $branchIds);
        }
    }

    protected function scopeByAcademyBranch($query, string $branchColumn = 'branch_id'): void
    {
        $branchIds = $this->getAdminAcademyBranchIds();

        if ($branchIds !== null) {
            $query->whereIn($branchColumn, $branchIds);
        }
    }

    protected function scopeByInfrastructure($query, string $infraColumn = 'infrastructure_id'): void
    {
        $infraIds = $this->getAdminInfrastructureIds();

        if ($infraIds !== null) {
            $query->whereIn($infraColumn, $infraIds);
        }
    }

    protected function validateInfrastructureAccess(int $infrastructureId): void
    {
        $infraIds = $this->getAdminInfrastructureIds();

        if ($infraIds !== null && !in_array($infrastructureId, $infraIds)) {
            throw new \App\Common\Exceptions\ApiException('No tiene acceso a esta infraestructura', 403);
        }
    }
}
