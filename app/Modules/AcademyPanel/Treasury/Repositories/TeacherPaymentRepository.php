<?php

namespace App\Modules\AcademyPanel\Treasury\Repositories;

use App\Models\Treasury\EmployeePayment;
use App\Models\Treasury\EmployeeAdvance;
use App\Models\Profile\Teacher;
use App\Common\Http\Context\AdminContext;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\DB;

class TeacherPaymentRepository
{
    public function dataTable($request)
    {
        $branchId = AdminContext::academyBranchId();

        $query = EmployeePayment::select('treasury_employee_payments.*')
            ->with([
                'employee' => function (MorphTo $morphTo) {
                    $morphTo->morphWith([Teacher::class => ['branch']]);
                },
                'paymentMethod',
                'paidBy',
            ])
            ->join('profile_teachers', 'profile_teachers.core_person_id', '=', 'treasury_employee_payments.employee_id')
            ->where('treasury_employee_payments.employee_type', 'profile_teachers')
            ->where('profile_teachers.branch_id', $branchId);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('treasury_employee_payments.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function paymentSummaryDataTable($request)
    {
        $branchId = AdminContext::academyBranchId();

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
            ->where('profile_teachers.branch_id', $branchId)
            ->where('profile_teachers.is_active', true);

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $items->orderBy('core_persons.name', 'asc');
        }

        return $items->dataTable($request);
    }

    public function ensureBelongsToBranch(int $teacherId): void
    {
        $branchId = AdminContext::academyBranchId();
        $exists = Teacher::where('core_person_id', $teacherId)
            ->where('branch_id', $branchId)
            ->exists();
        if (!$exists) {
            throw new \Exception('El docente no pertenece a la sede actual', 403);
        }
    }

    public function createOrUpdate(array $data): EmployeePayment
    {
        if (isset($data['id']) && $data['id']) {
            $payment = EmployeePayment::findOrFail($data['id']);
            if ($payment->status === 'cancelled') {
                throw new \Exception('No se puede editar un pago cancelado');
            }
        }

        if (empty($data['period']) && !empty($data['period_start'])) {
            $data['period'] = ucfirst(\Carbon\Carbon::parse($data['period_start'])
                ->locale('es')
                ->translatedFormat('F Y'));
        }

        $advanceIds = $data['calculation_details']['advance_ids'] ?? [];

        try {
            DB::beginTransaction();
            $payment = EmployeePayment::updateOrCreate(['id' => $data['id'] ?? null], $data);
            if (!empty($advanceIds)) {
                EmployeeAdvance::whereIn('id', $advanceIds)
                    ->where('status', 'pending')
                    ->update(['status' => 'discounted', 'discounted_in_payment_id' => $payment->id]);
            }
            DB::commit();
            return $payment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete(int $id): void
    {
        $payment = EmployeePayment::findOrFail($id);
        if ($payment->status === 'cancelled') {
            throw new \Exception('No se puede eliminar un pago cancelado');
        }
        $payment->delete();
    }
}
