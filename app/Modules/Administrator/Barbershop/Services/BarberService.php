<?php

namespace App\Modules\Administrator\Barbershop\Services;

use App\Modules\Administrator\Barbershop\Repositories\BarberRepository;
use App\Modules\Administrator\Barbershop\Repositories\Actions\CreateOrUpdateBarberAction;
use App\Modules\Auth\Repositories\Actions\CreateOrUpdateUserAction;

class BarberService
{
    public function __construct(
        private BarberRepository $barberRepository,
        private CreateOrUpdateBarberAction $createOrUpdateBarberAction,
    ) {}

    public function dataTable($request)
    {
        return $this->barberRepository->dataTable($request);
    }

    public function paymentSummaryDataTable($request)
    {
        return $this->barberRepository->paymentSummaryDataTable($request);
    }

    public function save($data)
    {
        return $this->createOrUpdateBarberAction->execute($data);
    }

    public function selectAsyncItems($request)
    {
        return $this->barberRepository->selectAsyncItems($request->search, $request->value, $request->infrastructureId);
    }

    public function paymentCalculation(int $barberId, string $periodStart, string $periodEnd): array
    {
        return $this->barberRepository->paymentCalculation($barberId, $periodStart, $periodEnd);
    }

    public function saveUser(array $data): void
    {
        $barber = $this->barberRepository->findByPersonId($data['id']);
        if (!$barber) throw new \App\Common\Exceptions\ApiException('Barbero no encontrado');

        $createOrUpdateUserAction = app(CreateOrUpdateUserAction::class);
        $createOrUpdateUserAction->execute($data['user']);

        $profileRepository = app(\App\Modules\Shared\Repositories\ProfileRepository::class);
        $barberProfile = $profileRepository->findUserIdAndType($data['user']['id'], 'barbers');
        if ($barberProfile) {
            $barberProfile->update(['is_active' => $data['user']['is_active']]);
        }
    }
}
