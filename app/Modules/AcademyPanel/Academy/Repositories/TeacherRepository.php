<?php

namespace App\Modules\AcademyPanel\Academy\Repositories;

use App\Models\Profile\Teacher;
use App\Common\Http\Context\AdminContext;
use Illuminate\Support\Facades\DB;

class TeacherRepository
{
    public function dataTable($request)
    {
        $branchId = AdminContext::academyBranchId();
        $items = Teacher::select(
            'profile_teachers.core_person_id as id', 'profile_teachers.branch_id', 'profile_teachers.payment_type',
            'profile_teachers.monthly_salary', 'profile_teachers.is_active',
            'academy_branches.name as branch_name',
            'core_persons.document_type as person_document_type', 'core_persons.document_number as person_document_number',
            'core_persons.name as person_name', 'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname', 'core_persons.email as person_email',
            'core_persons.phone as person_phone', 'core_persons.date_birth as person_date_birth',
            'core_persons.gender as person_gender', 'core_persons.address as person_address',
        )
            ->join('core_persons', 'profile_teachers.core_person_id', '=', 'core_persons.id')
            ->leftJoin('academy_branches', 'profile_teachers.branch_id', '=', 'academy_branches.id')
            ->where('profile_teachers.branch_id', $branchId);

        if (empty($request->sortBy)) { $items->orderBy('profile_teachers.core_person_id', 'desc'); }
        return $items->dataTable($request);
    }

    public function selectAsyncItems($search)
    {
        $branchId = AdminContext::academyBranchId();
        $items = Teacher::select(
            'profile_teachers.core_person_id as id',
            'core_persons.name as person_name', 'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname', 'core_persons.document_number as person_document_number',
        )
            ->join('core_persons', 'profile_teachers.core_person_id', '=', 'core_persons.id')
            ->where('profile_teachers.branch_id', $branchId)
            ->where('profile_teachers.is_active', true);

        if (!empty($search)) {
            $items->where(function ($q) use ($search) {
                $q->where('core_persons.name', 'like', "%{$search}%")
                    ->orWhere('core_persons.paternal_surname', 'like', "%{$search}%")
                    ->orWhere('core_persons.document_number', 'like', "%{$search}%");
            });
        }
        return $items->limit(20)->get();
    }
}