<?php

namespace App\Modules\Administrator\Setting\Repositories;

use App\Models\Core\Company;

class CompanyRepository
{
    public function getFirst()
    {
        return Company::first();
    }

    public function save(array $data)
    {
        $company = Company::first();

        if ($company) {
            $company->update($data);
            return $company;
        }

        // Solo crear si hay datos suficientes (no solo el logo)
        if (isset($data['name'])) {
            return Company::create($data);
        }

        // Si solo se está subiendo el logo y no hay empresa, crearla con defaults
        return Company::create(array_merge(['name' => 'Mi Empresa'], $data));
    }
}
