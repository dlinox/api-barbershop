<?php

namespace App\Modules\Administrator\Setting\Services;

use App\Modules\Administrator\Setting\Repositories\CompanyRepository;

class CompanyService
{
    public function __construct(
        private CompanyRepository $companyRepository
    ) {}

    public function get()
    {
        return $this->companyRepository->getFirst();
    }

    public function save(array $data)
    {
        return $this->companyRepository->save($data);
    }
}
