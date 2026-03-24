<?php

namespace App\Modules\Administrator\Treasury\Services;

use App\Common\Exceptions\ApiException;
use App\Models\Treasury\CashSession;
use App\Modules\Administrator\Treasury\Repositories\CashSessionRepository;
use App\Modules\Administrator\Treasury\Repositories\Actions\GenerateCashSessionPdfAction;

class CashSessionService
{
    public function __construct(
        private readonly CashSessionRepository $repository,
        private readonly GenerateCashSessionPdfAction $generatePdfAction,
    ) {}

    public function dataTable($request)
    {
        return $this->repository->dataTable($request);
    }

    public function getOpenSession(int $cashRegisterId): ?CashSession
    {
        return $this->repository->getOpenSession($cashRegisterId);
    }

    public function openSession(int $cashRegisterId, float $openingAmount, int $userId, ?string $notes): CashSession
    {
        $existing = $this->repository->getOpenSession($cashRegisterId);

        if ($existing) {
            throw new ApiException('Esta caja ya tiene una sesión abierta.');
        }

        return $this->repository->openSession($cashRegisterId, $openingAmount, $userId, $notes);
    }

    public function closeSession(int $cashSessionId, float $actualClosingAmount, int $userId, ?string $notes): CashSession
    {
        $session = CashSession::findOrFail($cashSessionId);

        if ($session->status !== 'open') {
            throw new ApiException('Esta sesión ya fue cerrada.');
        }

        return $this->repository->closeSession($session, $actualClosingAmount, $userId, $notes);
    }

    public function generateClosingPdf(int $cashSessionId): string
    {
        $session = CashSession::findOrFail($cashSessionId);

        if ($session->status !== 'closed') {
            throw new ApiException('La sesión aún no ha sido cerrada.');
        }

        return $this->generatePdfAction->execute($session);
    }
}
