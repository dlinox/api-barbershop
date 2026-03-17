<?php

namespace App\Modules\Administrator\Treasury\Repositories;

use App\Models\Treasury\Income;
use App\Common\Traits\HasInfrastructureScope;
use Illuminate\Http\Request;

class IncomeRepository
{
    use HasInfrastructureScope;

    public function dataTable(Request $request)
    {
        $query = Income::select(
            'treasury_incomes.*',
            'core_persons.name as person_name',
            'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname',
            'core_persons.document_number as person_document_number',
            'auth_users.username as user_username',
        )
            ->leftJoin('core_persons', 'core_persons.id', 'treasury_incomes.person_id')
            ->leftJoin('auth_users', 'auth_users.id', 'treasury_incomes.user_id');

        $this->scopeByInfrastructure($query, 'treasury_incomes.infrastructure_id');

        // if ($request->infrastructureId) {
        //     $query->where('treasury_incomes.infrastructure_id', $request->infrastructureId);
        // }

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('treasury_incomes.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function getById(int $id)
    {
        return Income::with(['details', 'paymentMethods.paymentMethod', 'person'])
            ->findOrFail($id);
    }
}
