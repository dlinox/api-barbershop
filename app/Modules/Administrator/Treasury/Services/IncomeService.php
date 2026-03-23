<?php

namespace App\Modules\Administrator\Treasury\Services;

use App\Models\Treasury\Income;
use App\Common\Helpers\FileHelper;
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

    private const PDF_TYPE = 'payment_receipt';

    public function generatePdf(int $id)
    {
        $income = Income::findOrFail($id);

        // ─── Buscar archivo existente ───
        $existingFile = $income->files()
            ->where('type', self::PDF_TYPE)
            ->first();

        // ─── Si no existe, generar con el action ───
        if (!$existingFile || !FileHelper::fileExists($existingFile->disk, $existingFile->path)) {
            $this->generateIncomePdfAction->execute($id);
            $existingFile = $income->files()->where('type', self::PDF_TYPE)->first();
        }

        $content = file_get_contents(FileHelper::getFilePath($existingFile->disk, $existingFile->path));

        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"{$existingFile->name}\"",
        ]);
    }
}
