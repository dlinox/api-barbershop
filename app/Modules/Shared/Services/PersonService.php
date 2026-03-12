<?php

namespace App\Modules\Shared\Services;

use App\Modules\Shared\Repositories\PersonRepository;

class PersonService
{
    public function __construct(
        private PersonRepository $personRepository,
    ) {}

    public function selectAsyncItems($request)
    {
        return $this->personRepository->selectAsyncItems($request->search, $request->value);
    }
}
