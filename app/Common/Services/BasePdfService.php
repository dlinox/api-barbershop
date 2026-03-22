<?php

namespace App\Common\Services;

use App\Models\Core\Company;
use Illuminate\Support\Facades\Auth;

abstract class BasePdfService
{
    protected function baseData(): array
    {
        $user = Auth::user();
        $company = Company::first();

        return [
            'company'      => $company,
            'generated_by' => $user?->username ?? 'Sistema',
            'generated_at' => now()->format('d/m/Y H:i'),
        ];
    }
}
