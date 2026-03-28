<?php

namespace App\Modules\BarberPanel\CashRegister\Services;

use App\Common\Exceptions\ApiException;
use App\Models\Profile\Barber;
use App\Models\Treasury\CashSession;
use App\Modules\BarberPanel\CashRegister\Repositories\CashRegisterRepository;
use App\Modules\BarberPanel\Shared\BarberContext;

class CashRegisterService
{
    public function __construct(
        private readonly CashRegisterRepository $repository,
    ) {}

    public function items()
    {
        $barber = Barber::findOrFail(BarberContext::barberId());
        return $this->repository->getByBranchId($barber->branch_id);
    }

    public function sessionStatus(int $cashRegisterId): array
    {
        $session = $this->repository->getOpenSession($cashRegisterId);

        if (!$session) {
            return ['is_open' => false];
        }

        return [
            'is_open'           => true,
            'session_id'        => $session->id,
            'opened_by'         => $session->openedByUser?->name ?? 'Desconocido',
            'opened_at'         => $session->opened_at?->format('H:i'),
            'infrastructure_id' => $session->cashRegister?->infrastructure_id,
        ];
    }

    public function openSession(int $cashRegisterId, float $openingAmount, int $userId, ?string $notes): CashSession
    {
        $existing = $this->repository->getOpenSession($cashRegisterId);

        if ($existing) {
            throw new ApiException('Esta caja ya tiene una sesión abierta.');
        }

        return $this->repository->openSession($cashRegisterId, $openingAmount, $userId, $notes);
    }
}
