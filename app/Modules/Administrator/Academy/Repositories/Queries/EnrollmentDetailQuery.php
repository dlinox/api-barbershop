<?php

namespace App\Modules\Administrator\Academy\Repositories\Queries;

use App\Models\Academy\Enrollment;
use App\Models\Academy\Enums\EnrollmentStatus;
use App\Models\Academy\Enums\PaymentPlanType;
use App\Models\Academy\Enums\Shift;
use App\Common\Enums\DayOfWeek;
use Carbon\Carbon;

class EnrollmentDetailQuery
{
    public function __invoke(int $id): Enrollment
    {
        return Enrollment::with([
            'student.person.documentTypeRelation',
            'student.person.genderRelation',
            'student.guardians',
            'group.branch',
            'group.level',
            'group.schedule',
            'group.paymentPlans',
            'payments' => fn($q) => $q->where('status', 'active'),
            'payments.details',
            'materials.presentation',
        ])->findOrFail($id);
    }

    public function toBladeData(Enrollment $enrollment): array
    {
        $person = $enrollment->student->person;
        $group = $enrollment->group;
        $schedule = $group->schedule;

        $daysFormatted = collect(explode(',', $group->days_of_week))
            ->map(fn($d) => DayOfWeek::tryFrom(trim($d))?->label() ?? trim($d))
            ->implode(', ');

        $paidPlanIds = $enrollment->payments
            ->flatMap(fn($payment) => $payment->details)
            ->pluck('group_payment_plan_id')
            ->unique();

        return [
            'enrollment_id' => $enrollment->id,
            'enrollment_date' => Carbon::parse($enrollment->date)->format('d/m/Y'),
            'enrollment_status' => EnrollmentStatus::tryFrom($enrollment->status)?->label() ?? $enrollment->status,

            'student_full_name' => $person->full_name,
            'document_type' => $person->documentTypeRelation?->name ?? strtoupper($person->document_type ?? ''),
            'document_number' => $person->document_number ?? '-',
            'date_birth' => $person->date_birth ? $person->date_birth->format('d/m/Y') : '-',
            'gender' => $person->genderRelation?->name ?? '-',
            'phone' => $person->phone ?? '-',
            'email' => $person->email ?? '-',
            'address' => $person->address ?? '-',

            'guardians' => $enrollment->student->guardians->map(fn($g) => [
                'full_name' => $g->full_name,
                'kinship' => $g->kinship,
                'phone' => $g->phone,
            ])->toArray(),

            'group_name' => $group->name,
            'level_name' => $group->level->name,
            'branch_name' => $group->branch->name,
            'schedule_shift' => Shift::tryFrom($schedule->shift)?->label() ?? $schedule->shift,
            'schedule_time' => substr($schedule->start_time, 0, 5) . ' - ' . substr($schedule->end_time, 0, 5),
            'days_of_week' => $group->days_of_week,
            'start_date' => Carbon::parse($group->start_date)->format('d/m/Y'),
            'end_date' => Carbon::parse($group->end_date)->format('d/m/Y'),

            'payment_plans' => $group->paymentPlans->map(function ($p) use ($paidPlanIds) {
                return [
                    'type' => PaymentPlanType::tryFrom($p->type)?->label() ?? $p->type,
                    'start_date' => Carbon::parse($p->start_date)->format('d/m/Y'),
                    'end_date' => Carbon::parse($p->end_date)->format('d/m/Y'),
                    'amount' => $p->amount,
                    'is_paid' => $paidPlanIds->contains($p->id),
                ];
            })->toArray(),

            'materials' => $enrollment->materials->map(fn($m) => [
                'name' => $m->presentation?->name ?? "Material #{$m->id}",
                'quantity' => $m->pivot->quantity,
            ])->toArray(),
        ];
    }
}
