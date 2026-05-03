<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Enrollment;

use App\Models\Academy\Enums\EnrollmentStatus;
use App\Models\Academy\Enums\PaymentPlanType;
use App\Models\Academy\Enums\Shift;
use App\Common\Enums\DayOfWeek;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class EnrollmentDetailResource extends JsonResource
{
    public function toArray($request)
    {
        $person = $this->student->person;
        $group = $this->group;
        $schedule = $group->schedule;

        $daysFormatted = collect(explode(',', $group->days_of_week))
            ->map(fn($d) => DayOfWeek::tryFrom(trim($d))?->label() ?? trim($d))
            ->implode(', ');

        $paidPlanIds = $this->payments
            ->where('status', 'active')
            ->flatMap(fn($payment) => $payment->details)
            ->pluck('group_payment_plan_id')
            ->unique();

        return [
            'enrollmentId' => (int) $this->id,
            'enrollmentDate' => Carbon::parse($this->date)->format('d/m/Y'),
            'enrollmentStatus' => EnrollmentStatus::tryFrom($this->status)?->label() ?? $this->status,

            'studentFullName' => $person->full_name,
            'documentType' => $person->documentTypeRelation?->name ?? strtoupper($person->document_type ?? ''),
            'documentNumber' => $person->document_number ?? '-',
            'dateBirth' => $person->date_birth ? $person->date_birth->format('d/m/Y') : '-',
            'gender' => $person->genderRelation?->name ?? '-',
            'phone' => $person->phone ?? '-',
            'email' => $person->email ?? '-',
            'address' => $person->address ?? '-',

            'guardians' => $this->student->guardians->map(fn($g) => [
                'fullName' => $g->full_name,
                'kinship' => $g->kinship,
                'phone' => $g->phone,
            ])->toArray(),

            'groupName' => $group->name,
            'levelName' => $group->level->name,
            'branchName' => $group->branch->name,
            'scheduleShift' => Shift::tryFrom($schedule->shift)?->label() ?? $schedule->shift,
            'scheduleTime' => substr($schedule->start_time, 0, 5) . ' - ' . substr($schedule->end_time, 0, 5),
            'daysOfWeek' => $daysFormatted,
            'startDate' => Carbon::parse($group->start_date)->format('d/m/Y'),
            'endDate' => Carbon::parse($group->end_date)->format('d/m/Y'),

            'paymentPlans' => $group->paymentPlans->map(function ($p) use ($paidPlanIds) {
                return [
                    'type' => PaymentPlanType::tryFrom($p->type)?->label() ?? $p->type,
                    'startDate' => Carbon::parse($p->start_date)->format('d/m/Y'),
                    'endDate' => Carbon::parse($p->end_date)->format('d/m/Y'),
                    'amount' => (float) $p->amount,
                    'isPaid' => $paidPlanIds->contains($p->id),
                ];
            })->toArray(),

            'materials' => $this->materials->map(fn($m) => [
                'name' => $m->presentation?->name ?? "Material #{$m->id}",
                'quantity' => (int) $m->pivot->quantity,
            ])->toArray(),

            'groupChangeId' => $this->groupChangeAsOrigin?->id ?? $this->groupChangeAsDestination?->id,
        ];
    }
}
