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

        return Company::create($data);
    }
}
