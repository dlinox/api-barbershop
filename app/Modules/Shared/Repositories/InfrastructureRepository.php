<?php

namespace App\Modules\Shared\Repositories;

use App\Models\Core\Infrastructure;

class InfrastructureRepository
{
    public function getSelectItems()
    {
        return Infrastructure::with('infrastructurable')->get();
    }
}
