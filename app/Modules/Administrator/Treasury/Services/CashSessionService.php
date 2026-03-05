<?php

namespace App\Modules\Administrator\Treasury\Services;

use App\Common\Exceptions\ApiException;
use App\Models\Treasury\CashSession;
use App\Modules\Administrator\Treasury\Repositories\CashSessionRepository;

class CashSessionService
{
    public function __construct(
        private readonly CashSessionRepository $repository,
    ) {}

    public function dataTable($request, int $cashRegisterId)
    {
        return $this->repository->dataTable($request, $cashRegisterId);
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
}
