<?php

namespace App\Modules\Administrator\Academy\Repositories;

use App\Models\Profile\Teacher;
use Illuminate\Support\Facades\DB;

class TeacherRepository
{
    public function dataTable($request)
    {
        $items = Teacher::select(
            //profile_teachers id |  person id
            'profile_teachers.core_person_id as id',

            //teacher
            'profile_teachers.branch_id',
            'profile_teachers.payment_type',
            'profile_teachers.monthly_salary',
            'profile_teachers.is_active',

            //branch
            'academy_branches.name as branch_name',

            //person
            'core_persons.document_type as person_document_type',
            'core_persons.document_number as person_document_number',
            'core_persons.name as person_name',
            'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname',
            'core_persons.email as person_email',
            'core_persons.phone as person_phone',
            'core_persons.date_birth as person_date_birth',
            'core_persons.gender as person_gender',
            'core_persons.address as person_address',
        )
            ->join('core_persons', 'profile_teachers.core_person_id', '=', 'core_persons.id')
            ->leftJoin('academy_branches', 'profile_teachers.branch_id', '=', 'academy_branches.id');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $items->orderBy('profile_teachers.core_person_id', 'desc');
        }

        $items = $items->dataTable($request);
        return $items;
    }

    public function userDataTable($request)
    {
        $items = Teacher::select(
            'profile_teachers.core_person_id as id',

            //person
            'core_persons.name as person_name',
            'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname',

            //user
            'auth_users.id as user_id',
            'auth_users.username as user_username',
            'auth_users.email as user_email',
            'auth_users.is_active as user_is_active',
        )
            ->join('core_persons', 'profile_teachers.core_person_id', '=', 'core_persons.id')
            ->join('behavior_profiles', function ($join) {
                $join->on('profile_teachers.core_person_id', '=', 'behavior_profiles.profileable_id')
                    ->where('behavior_profiles.profileable_type', 'profile_teachers');
            })
            ->join('auth_users', 'behavior_profiles.auth_user_id', '=', 'auth_users.id');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $items->orderBy('core_persons.name', 'asc');
        }

        $items = $items->dataTable($request);
        return $items;
    }

    public function findByPersonId(int $personId): ?Teacher
    {
        return Teacher::where('core_person_id', $personId)->first();
    }

    public function paymentSummaryDataTable($request)
    {
        $items = Teacher::select(
            'profile_teachers.core_person_id as id',
            DB::raw("CONCAT(core_persons.name, ' ', COALESCE(core_persons.paternal_surname, ''), ' ', COALESCE(core_persons.maternal_surname, '')) as full_name"),
            'academy_branches.name as branch_name',
            'profile_teachers.payment_type',
            'profile_teachers.monthly_salary',
            DB::raw("(SELECT COUNT(*) FROM academy_group_teachers gt WHERE gt.teacher_id = profile_teachers.core_person_id AND gt.status = 'active') as total_groups"),
            DB::raw("(SELECT MAX(ep.payment_date) FROM treasury_employee_payments ep WHERE ep.employee_type = 'profile_teachers' AND ep.employee_id = profile_teachers.core_person_id AND ep.status = 'paid') as last_payment_date"),
            DB::raw("(SELECT COALESCE(SUM(ep.total_amount), 0) FROM treasury_employee_payments ep WHERE ep.employee_type = 'profile_teachers' AND ep.employee_id = profile_teachers.core_person_id AND ep.status = 'paid') as total_paid"),
        )
            ->join('core_persons', 'profile_teachers.core_person_id', '=', 'core_persons.id')
            ->leftJoin('academy_branches', 'profile_teachers.branch_id', '=', 'academy_branches.id')
            ->where('profile_teachers.is_active', true);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $items->orderBy('core_persons.name', 'asc');
        }

        $items = $items->dataTable($request);
        return $items;
    }

    public function create(int $personId, ?int $branchId = null, ?string $paymentType = null, ?float $monthlySalary = null, bool $isActive = true): Teacher
    {
        return Teacher::create([
            'core_person_id' => $personId,
            'branch_id'      => $branchId,
            'payment_type'   => $paymentType,
            'monthly_salary' => $monthlySalary,
            'is_active'      => $isActive,
        ]);
    }

    public function update(Teacher $teacher, ?int $branchId, ?string $paymentType, ?float $monthlySalary, bool $isActive): Teacher
    {
        $teacher->update([
            'branch_id'      => $branchId,
            'payment_type'   => $paymentType,
            'monthly_salary' => $monthlySalary,
            'is_active'      => $isActive,
        ]);
        return $teacher;
    }

    public function selectAsyncItems($search, $branchId = null)
    {
        $items = Teacher::select(
            'profile_teachers.core_person_id as id',
            'core_persons.name as person_name',
            'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname',
            'core_persons.document_number as person_document_number',
        )
            ->join('core_persons', 'profile_teachers.core_person_id', '=', 'core_persons.id');

        if (!empty($branchId)) {
            $items->where('profile_teachers.branch_id', $branchId);
        }

        if (!empty($search)) {
            $items->where(function ($query) use ($search) {
                $query->where('core_persons.name', 'like', "%{$search}%")
                    ->orWhere('core_persons.paternal_surname', 'like', "%{$search}%")
                    ->orWhere('core_persons.maternal_surname', 'like', "%{$search}%")
                    ->orWhere('core_persons.document_number', 'like', "%{$search}%")
                    ->orWhere(DB::raw('CONCAT(core_persons.name, " ", core_persons.paternal_surname, " ", core_persons.maternal_surname)'), 'like', "%{$search}%");
            });
        }

        return $items->limit(100)->get();
    }
}
