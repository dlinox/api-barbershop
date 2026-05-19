<?php

namespace App\Common\Helpers;

class PdfLogoHelper
{
    public static function resolve($branch = null, $company = null): ?string
    {
        if ($branch && !empty($branch->logo)) {
            $path = storage_path('app/public/branch_logos/' . basename($branch->logo));
            if (is_file($path)) {
                return $path;
            }
        }

        if ($company && !empty($company->logo)) {
            $path = storage_path('app/public/company_logos/' . basename($company->logo));
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }

    public static function branchFromInfrastructure($infrastructure)
    {
        if (!$infrastructure) {
            return null;
        }

        if (isset($infrastructure->logo)) {
            return $infrastructure;
        }

        try {
            return $infrastructure->infrastructurable ?? null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
