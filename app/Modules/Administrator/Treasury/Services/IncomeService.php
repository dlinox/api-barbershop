<?php

namespace App\Modules\Administrator\Treasury\Services;

use App\Modules\Administrator\Treasury\Repositories\IncomeRepository;
use App\Modules\Administrator\Treasury\Repositories\Actions\AnnulIncomeAction;
use App\Modules\Administrator\Treasury\Repositories\Actions\GenerateIncomePdfAction;

class IncomeService
{
    public function __construct(
        private readonly IncomeRepository $incomeRepository,
        private readonly AnnulIncomeAction $annulIncomeAction,
        private readonly GenerateIncomePdfAction $generateIncomePdfAction,
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

    public function update(int $id, array $data)
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($id, $data) {
            return $this->incomeRepository->update($id, $data);
        });
    }

    public function audits(int $id)
    {
        return $this->incomeRepository->getAudits($id);
    }

    /**
     * Genera el PDF siempre on-the-fly para garantizar datos actualizados.
     * El archivo anterior en disco se elimina si existe (ya no se cachea).
     */
    public function generatePdf(int $id)
    {
        return $this->generateIncomePdfAction->execute($id);
    }
}

