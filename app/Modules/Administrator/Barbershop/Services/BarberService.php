<?php

namespace App\Modules\Administrator\Barbershop\Services;

use App\Modules\Administrator\Barbershop\Repositories\BarberRepository;
use App\Modules\Administrator\Barbershop\Repositories\Actions\CreateOrUpdateBarberAction;
use App\Modules\Auth\Repositories\Actions\CreateOrUpdateUserAction;
use App\Modules\Shared\Repositories\ProfileRepository;

class BarberService
{
    public function __construct(
        private BarberRepository $barberRepository,
        private CreateOrUpdateBarberAction $createOrUpdateBarberAction,
        private CreateOrUpdateUserAction $createOrUpdateUserAction,
        private ProfileRepository $profileRepository,
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

    public function userDataTable($request)
    {
        return $this->barberRepository->userDataTable($request);
    }

    public function saveUser(array $data)
    {
        $this->createOrUpdateUserAction->execute($data['user']);

        $profile = $this->profileRepository->findByProfileableIdAndType($data['id'], 'barbers');
        if ($profile) {
            $profile->update(['is_active' => $data['user']['is_active']]);
        }
    }

    public function selectAsyncItems($request)
    {
        return $this->barberRepository->selectAsyncItems($request->search, $request->value, $request->infrastructureId);
    }

    public function paymentCalculation(int $barberId, string $periodStart, string $periodEnd): array
    {
        return $this->barberRepository->paymentCalculation($barberId, $periodStart, $periodEnd);
    }

}
