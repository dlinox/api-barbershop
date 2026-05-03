<?php

namespace App\Modules\BarbershopPanel\Treasury\Services;

use App\Modules\BarbershopPanel\Treasury\Repositories\IncomeRepository;
use App\Modules\Administrator\Treasury\Repositories\Actions\AnnulIncomeAction;
use App\Modules\Administrator\Treasury\Repositories\Actions\GenerateIncomePdfAction;
use Illuminate\Http\Request;

class IncomeService
{
    public function __construct(
        private readonly IncomeRepository $incomeRepository,
        private readonly AnnulIncomeAction $annulIncomeAction,
        private readonly GenerateIncomePdfAction $generateIncomePdfAction,
    ) {}

    public function dataTable(Request $request)
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

    private const PDF_TYPE = 'payment_receipt';

    public function generatePdf(int $id)
    {
        return $this->generateIncomePdfAction->execute($id);
    }
}
