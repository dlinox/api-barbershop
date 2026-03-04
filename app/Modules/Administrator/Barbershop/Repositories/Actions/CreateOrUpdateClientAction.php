<?php

namespace App\Modules\Administrator\Barbershop\Repositories\Actions;

use Illuminate\Support\Facades\DB;

use App\Common\Exceptions\ApiException;

use App\Modules\Administrator\Barbershop\Repositories\ClientRepository;
use App\Modules\Shared\Repositories\Actions\CreateOrUpdatePersonAction;

class CreateOrUpdateClientAction
{
    public function __construct(
        private ClientRepository $clientRepository,
        private CreateOrUpdatePersonAction $createOrUpdatePersonAction,
    ) {}

    public function execute(array $data): void
    {
        try {
            DB::beginTransaction();

            $person = $this->createOrUpdatePersonAction->execute($data['person'], $data['id']);

            if (!$data['id']) {
                $client = $this->clientRepository->findByPersonId($person->id);
                if ($client) throw new ApiException('La persona ya tiene un perfil de cliente');
                $client = $this->clientRepository->create($person->id);
                if (!$client) throw new ApiException('Error al crear el perfil de cliente');
            } else {
                $client = $this->clientRepository->findByPersonId($data['id']);
                if (!$client) throw new ApiException('Error al encontrar el perfil de cliente');
                if ($data['id'] != $person->id) throw new ApiException('El perfil de cliente no coincide con la persona');
            }

            DB::commit();
        } catch (ApiException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiException($e->getMessage());
        }
    }
}
