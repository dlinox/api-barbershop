<?php

namespace App\Modules\Administrator\Inventory\Repositories\Actions;

use App\Models\Core\Person;
use App\Models\Profile\Client;
use App\Common\Exceptions\ApiException;

class EnsureClientProfileAction
{
    /**
     * Verifica que la persona exista y tenga perfil de cliente.
     * Si no tiene perfil de cliente, lo crea.
     */
    public function execute(int $personId): Person
    {
        $person = Person::find($personId);

        if (!$person) {
            throw new ApiException('La persona no existe.');
        }

        $client = Client::find($personId);

        if (!$client) {
            Client::create(['id' => $personId]);
        }

        return $person;
    }
}
