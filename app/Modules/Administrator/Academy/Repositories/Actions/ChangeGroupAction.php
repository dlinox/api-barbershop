<?php

namespace App\Modules\Administrator\Academy\Repositories\Actions;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Common\Exceptions\ApiException;
use App\Models\Academy\Enrollment;
use App\Models\Academy\Group;
use App\Models\Academy\Attendance;
use App\Models\Academy\AttendanceDeadline;
use App\Models\Academy\EnrollmentGroupChange;
use App\Models\Academy\EnrollmentPaymentDetail;
use App\Models\Academy\GroupPaymentPlan;

class ChangeGroupAction
{
    /**
     * Transfer a student from one group to another.
     *
     * Steps:
     *  1. Validate business rules
     *  2. Build plan mapping (origin group plans → destination group plans)
     *  3. In a transaction:
     *     a. Create new enrollment in destination group
     *     b. Cancel origin enrollment
     *     c. Re-link EnrollmentPayments to new enrollment
     *     d. Re-map EnrollmentPaymentDetails group_payment_plan_id
     *     e. Transfer materials
     *     f. Transfer attendances (matching by date in destination group)
     *     g. Create audit record
     */
    public function execute(
        int $originEnrollmentId,
        int $targetGroupId,
        ?string $reason,
        int $userId,
    ): Enrollment {
        // ─── 1. Load and validate origin enrollment ───
        $originEnrollment = Enrollment::with([
            'payments.details',
            'materials',
        ])->find($originEnrollmentId);

        if (!$originEnrollment) {
            throw new ApiException('La matrícula de origen no existe.');
        }

        if ($originEnrollment->status !== 'active') {
            throw new ApiException('Solo se puede cambiar de grupo una matrícula activa.');
        }

        if ($originEnrollment->group_id === $targetGroupId) {
            throw new ApiException('El estudiante ya pertenece a ese grupo.');
        }

        // ─── Validate target group ───
        $targetGroup = Group::find($targetGroupId);
        if (!$targetGroup) {
            throw new ApiException('El grupo destino no existe.');
        }

        if (!in_array($targetGroup->status, ['active', 'coming'])) {
            throw new ApiException('Solo se puede cambiar a un grupo activo o próximo.');
        }

        // ─── Student must not already have an active enrollment in the target group ───
        $existingEnrollment = Enrollment::where('profile_student_id', $originEnrollment->profile_student_id)
            ->where('group_id', $targetGroupId)
            ->where('status', 'active')
            ->first();

        if ($existingEnrollment) {
            throw new ApiException('El estudiante ya tiene una matrícula activa en el grupo destino.');
        }

        // ─── 2. Build plan mapping ───
        $planMapping = $this->buildPlanMapping($originEnrollment, $targetGroupId);

        // ─── 3. Transaction ───
        try {
            DB::beginTransaction();

            // a. Create new enrollment in destination group
            $newEnrollment = Enrollment::create([
                'profile_student_id' => $originEnrollment->profile_student_id,
                'group_id'           => $targetGroupId,
                'date'               => Carbon::today()->format('Y-m-d'),
                'status'             => 'active',
            ]);

            // b. Cancel origin enrollment
            $originEnrollment->update(['status' => 'cancelled']);

            // c. Re-link EnrollmentPayments → new enrollment
            DB::table('academy_enrollment_payments')
                ->where('enrollment_id', $originEnrollmentId)
                ->update(['enrollment_id' => $newEnrollment->id]);

            // d. Re-map EnrollmentPaymentDetails plan references
            if (!empty($planMapping)) {
                foreach ($planMapping as $originPlanId => $targetPlanId) {
                    DB::table('academy_enrollment_payment_details')
                        ->whereIn('enrollment_payment_id', function ($q) use ($newEnrollment) {
                            $q->select('id')
                                ->from('academy_enrollment_payments')
                                ->where('enrollment_id', $newEnrollment->id);
                        })
                        ->where('group_payment_plan_id', $originPlanId)
                        ->update(['group_payment_plan_id' => $targetPlanId]);
                }
            }

            // e. Transfer materials
            $this->transferMaterials($originEnrollmentId, $newEnrollment->id);

            // f. Transfer attendances (matching date in target group)
            $this->transferAttendances($originEnrollmentId, $newEnrollment->id, $targetGroupId);

            // g. Audit record
            $changeRecord = EnrollmentGroupChange::create([
                'origin_enrollment_id'      => $originEnrollmentId,
                'destination_enrollment_id' => $newEnrollment->id,
                'reason'                    => $reason,
                'changed_by_user_id'        => $userId,
                'changed_at'                => Carbon::now(),
            ]);

            DB::commit();

            $newEnrollment->changeId = $changeRecord->id;
            return $newEnrollment;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Build a map of [ originPlanId => targetPlanId ] for all plans referenced
     * by the student's payment history.
     *
     * Matching rules:
     *  - type='enrollment' → the enrollment-type plan of the target group
     *  - type='monthly'    → positional match: origin monthly plan #N → target monthly plan #N
     *                        (both sorted by start_date ASC). If target has fewer plans the
     *                        remaining origin plans keep their original reference.
     */
    private function buildPlanMapping(Enrollment $originEnrollment, int $targetGroupId): array
    {
        // Collect distinct origin plan IDs from all payment details
        $originPlanIds = DB::table('academy_enrollment_payment_details')
            ->join('academy_enrollment_payments', 'academy_enrollment_payment_details.enrollment_payment_id', '=', 'academy_enrollment_payments.id')
            ->where('academy_enrollment_payments.enrollment_id', $originEnrollment->id)
            ->pluck('academy_enrollment_payment_details.group_payment_plan_id')
            ->unique()
            ->all();

        if (empty($originPlanIds)) {
            return [];
        }

        $originPlans = GroupPaymentPlan::whereIn('id', $originPlanIds)->get()->keyBy('id');
        $targetPlans = GroupPaymentPlan::where('group_id', $targetGroupId)->get();

        $enrollmentTargetPlan = $targetPlans->firstWhere('type', 'enrollment');
        $monthlyTargetPlans   = $targetPlans->where('type', 'monthly')->sortBy('start_date')->values();

        // Sort origin monthly plans by start_date to build positional mapping
        $originMonthlyPlans = $originPlans->filter(fn($p) => $p->type === 'monthly')
            ->sortBy('start_date')
            ->values();

        $mapping = [];

        foreach ($originPlans as $originPlan) {
            if ($originPlan->type === 'enrollment') {
                if ($enrollmentTargetPlan) {
                    $mapping[$originPlan->id] = $enrollmentTargetPlan->id;
                }
                // If no enrollment plan in target: keep original reference (no mapping entry)
                continue;
            }

            // monthly: positional — find index of this plan among origin monthly plans
            $index = $originMonthlyPlans->search(fn($p) => $p->id === $originPlan->id);

            if ($index !== false && isset($monthlyTargetPlans[$index])) {
                $mapping[$originPlan->id] = $monthlyTargetPlans[$index]->id;
            }
            // If no target plan at this position: keep original reference (no mapping entry)
        }

        return $mapping;
    }

    /**
     * Copy material records from origin enrollment to the new enrollment.
     * Only copies materials not already present in the new enrollment.
     */
    private function transferMaterials(int $originEnrollmentId, int $newEnrollmentId): void
    {
        $materials = DB::table('academy_enrollment_materials')
            ->where('enrollment_id', $originEnrollmentId)
            ->get();

        foreach ($materials as $material) {
            $exists = DB::table('academy_enrollment_materials')
                ->where('enrollment_id', $newEnrollmentId)
                ->where('material_id', $material->material_id)
                ->exists();

            if (!$exists) {
                DB::table('academy_enrollment_materials')->insert([
                    'enrollment_id' => $newEnrollmentId,
                    'material_id'   => $material->material_id,
                    'quantity'      => $material->quantity,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }
    }

    /**
     * Transfer attendances from origin enrollment to new enrollment.
     * Only transfers records where a matching AttendanceDeadline exists
     * in the target group with the same date.
     */
    private function transferAttendances(int $originEnrollmentId, int $newEnrollmentId, int $targetGroupId): void
    {
        $attendances = Attendance::with('attendanceDeadline')
            ->where('enrollment_id', $originEnrollmentId)
            ->get();

        if ($attendances->isEmpty()) {
            return;
        }

        // Load all deadlines for target group indexed by date
        $targetDeadlines = AttendanceDeadline::where('group_id', $targetGroupId)
            ->get()
            ->keyBy('date');

        foreach ($attendances as $attendance) {
            $date = $attendance->attendanceDeadline?->date;
            if (!$date) {
                continue;
            }

            $targetDeadline = $targetDeadlines->get($date);
            if (!$targetDeadline) {
                continue;
            }

            // Avoid duplicate (unique constraint: enrollment_id + attendance_deadline_id)
            $exists = Attendance::where('enrollment_id', $newEnrollmentId)
                ->where('attendance_deadline_id', $targetDeadline->id)
                ->exists();

            if (!$exists) {
                Attendance::create([
                    'enrollment_id'         => $newEnrollmentId,
                    'attendance_deadline_id' => $targetDeadline->id,
                    'check_in'              => $attendance->check_in,
                    'check_out'             => $attendance->check_out,
                    'observation'           => $attendance->observation,
                    'status'                => $attendance->status,
                ]);
            }
        }
    }
}
