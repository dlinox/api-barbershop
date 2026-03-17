<?php

namespace App\Modules\Administrator\Treasury\Services;

use App\Modules\Administrator\Treasury\Repositories\IncomeRepository;
use App\Modules\Administrator\Treasury\Repositories\Actions\AnnulIncomeAction;

class IncomeService
{
    public function __construct(
        private readonly IncomeRepository $incomeRepository,
        private readonly AnnulIncomeAction $annulIncomeAction,
    ) {}

    public function dataTable($request)
    {
        return $this->incomeRepository->dataTable($request);
    }

    public function getById(int $id)
    {
        return $this->incomeRepository->getById($id);
    }

    public function annul(int $id)
    {
        return $this->annulIncomeAction->execute($id);
    }
}
