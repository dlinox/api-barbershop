<?php

namespace App\Modules\Administrator\Barbershop\Repositories;

use App\Models\Profile\Barber;
use App\Models\Barbershop\Ticket;
use App\Models\Barbershop\BarberAttendance;
use App\Models\Treasury\EmployeeAdvance;
use App\Models\Treasury\Income;
use App\Common\Traits\HasInfrastructureScope;
use Illuminate\Support\Facades\DB;

class BarberRepository
{
    use HasInfrastructureScope;
    public function dataTable($request)
    {
        $items = Barber::select(
            //profile_barbers id |  person id
            'profile_barbers.id as id',
            'profile_barbers.branch_id',
            'profile_barbers.commission_percentage',
            'profile_barbers.is_active',

            //branch
            'barbershop_branches.name as branch_name',

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
            ->join('core_persons', 'profile_barbers.id', '=', 'core_persons.id')
            ->join('barbershop_branches', 'profile_barbers.branch_id', '=', 'barbershop_branches.id');

        $this->scopeByBranch($items, 'profile_barbers.branch_id');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $items->orderBy('profile_barbers.id', 'desc');
        }

        $items = $items->dataTable($request);
        return $items;
    }

    public function userDataTable($request)
    {
        $items = Barber::select(
            'profile_barbers.id as id',

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
            ->join('core_persons', 'profile_barbers.id', '=', 'core_persons.id')
            ->join('behavior_profiles', function ($join) {
                $join->on('profile_barbers.id', '=', 'behavior_profiles.profileable_id')
                    ->where('behavior_profiles.profileable_type', 'profile_barbers');
            })
            ->join('auth_users', 'behavior_profiles.auth_user_id', '=', 'auth_users.id');

        $this->scopeByBranch($items, 'profile_barbers.branch_id');

        // Fix: apply is_active with qualified column to avoid ambiguity across joined tables
        $filters = is_array($request->filters) ? $request->filters : [];
        if (array_key_exists('isActive', $filters)) {
            if (!is_null($filters['isActive'])) {
                $items->where('auth_users.is_active', $filters['isActive']);
            }
            $request->merge(['filters' => collect($filters)->except('isActive')->all()]);
        }

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $items->orderBy('core_persons.name', 'asc');
        }

        $userSearchColumns = [
            'core_persons.name',
            'core_persons.paternal_surname',
            'core_persons.maternal_surname',
            'auth_users.username',
            'auth_users.email',
        ];

        $items = $items->dataTable($request, $userSearchColumns);
        return $items;
    }

    public function findByPersonId(int $personId): ?Barber
    {
        return Barber::where('id', $personId)->first();
    }

    public function paymentSummaryDataTable($request)
    {
        $items = Barber::select(
            'profile_barbers.id as id',
            DB::raw("CONCAT(core_persons.name, ' ', COALESCE(core_persons.paternal_surname, ''), ' ', COALESCE(core_persons.maternal_surname, '')) as full_name"),
            'barbershop_branches.name as branch_name',
            'profile_barbers.commission_percentage as commission_percentage',
            DB::raw("(SELECT MAX(ep.payment_date) FROM treasury_employee_payments ep WHERE ep.employee_type = 'profile_barbers' AND ep.employee_id = profile_barbers.id AND ep.status = 'paid') as last_payment_date"),
            DB::raw("(SELECT COALESCE(SUM(ep.total_amount), 0) FROM treasury_employee_payments ep WHERE ep.employee_type = 'profile_barbers' AND ep.employee_id = profile_barbers.id AND ep.status = 'paid') as total_paid"),
        )
            ->join('core_persons', 'profile_barbers.id', '=', 'core_persons.id')
            ->join('barbershop_branches', 'profile_barbers.branch_id', '=', 'barbershop_branches.id')
            ->where('profile_barbers.is_active', true);

        $this->scopeByBranch($items, 'profile_barbers.branch_id');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $items->orderBy('core_persons.name', 'asc');
        }

        $items = $items->dataTable($request);
        return $items;
    }

    public function create(int $personId, array $data): Barber
    {
        return Barber::create([
            'id' => $personId,
            'branch_id' => $data['branch_id'],
            'commission_percentage' => $data['commission_percentage'],
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function update(int $personId, array $data): void
    {
        Barber::where('id', $personId)->update([
            'branch_id' => $data['branch_id'],
            'commission_percentage' => $data['commission_percentage'],
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function selectAsyncItems($search, $value = null, $infrastructureId = null)
    {
        $selected = null;
        $limit = 25;

        $query = Barber::select(
            'profile_barbers.id as id',
            'core_persons.name as person_name',
            'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname',
            'core_persons.document_number as person_document_number',
        )
            ->join('core_persons', 'profile_barbers.id', '=', 'core_persons.id');

        if (!empty($infrastructureId)) {
            $query->join('core_infrastructures', function ($join) use ($infrastructureId) {
                $join->on('profile_barbers.branch_id', '=', 'core_infrastructures.infrastructurable_id')
                    ->where('core_infrastructures.infrastructurable_type', 'barbershop_branches')
                    ->where('core_infrastructures.id', $infrastructureId);
            });
        } else {
            $this->scopeByBranch($query, 'profile_barbers.branch_id');
        }

        if (!empty($value)) {
            $selected = (clone $query)->where('profile_barbers.id', $value)->first();
            if ($selected) {
                $limit = 24;
            }
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('core_persons.name', 'like', "%{$search}%")
                    ->orWhere('core_persons.paternal_surname', 'like', "%{$search}%")
                    ->orWhere('core_persons.maternal_surname', 'like', "%{$search}%")
                    ->orWhere('core_persons.document_number', 'like', "%{$search}%")
                    ->orWhere(DB::raw('CONCAT(core_persons.name, " ", core_persons.paternal_surname, " ", core_persons.maternal_surname)'), 'like', "%{$search}%");
            });
        }

        if ($selected) {
            $query->where('profile_barbers.id', '!=', $value);
        }

        $items = $query->limit($limit)->get();

        if ($selected) {
            $items->prepend($selected);
        }

        return $items;
    }

    public function paymentCalculation(int $barberId, string $periodStart, string $periodEnd): array
    {
        $tickets = Ticket::where('profile_barber_id', $barberId)
            ->whereDate('ticket_date', '>=', $periodStart)
            ->whereDate('ticket_date', '<=', $periodEnd)
            ->where('status', 'confirmed')
            ->orderBy('ticket_date', 'asc')
            ->get();

        $ticketsTotal = $tickets->sum('total');
        $ticketsCount = $tickets->count();

        $ticketIds = $tickets->pluck('id');
        $incomes = Income::where('transactionable_type', 'barbershop_tickets')
            ->whereIn('transactionable_id', $ticketIds)
            ->get()
            ->keyBy('transactionable_id');

        $advances = EmployeeAdvance::where('employee_type', 'profile_barbers')
            ->where('employee_id', $barberId)
            ->where('status', 'pending')
            ->whereBetween('advance_date', [$periodStart, $periodEnd])
            ->get();

        $advancesTotal = $advances->sum('amount');

        $attendances = BarberAttendance::where('barber_id', $barberId)
            ->whereBetween('date', [$periodStart, $periodEnd])
            ->orderBy('date', 'asc')
            ->get();

        $absencesCount = $attendances->where('status', 'absent')->count();

        return [
            'tickets' => [
                'count' => $ticketsCount,
                'total' => (float) $ticketsTotal,
                'items' => $tickets->map(function ($t) use ($incomes) {
                    $income = $incomes->get($t->id);
                    return [
                        'id' => $t->id,
                        'ticketDate' => $t->ticket_date->format('Y-m-d'),
                        'amount' => (float) $t->amount,
                        'discount' => (float) $t->discount,
                        'total' => (float) $t->total,
                        'status' => $t->status,
                        'receiptSerie' => $income?->receipt_serie,
                        'receiptNumber' => $income?->receipt_number,
                    ];
                }),
            ],
            'advances' => [
                'total' => (float) $advancesTotal,
                'items' => $advances->map(fn($a) => [
                    'id' => $a->id,
                    'amount' => (float) $a->amount,
                    'date' => $a->advance_date->format('Y-m-d'),
                    'reason' => $a->reason,
                ]),
            ],
            'absences' => [
                'count' => $absencesCount,
            ],
            'attendances' => [
                'items' => $attendances->map(fn($a) => [
                    'id' => $a->id,
                    'date' => $a->date->format('Y-m-d'),
                    'status' => $a->status,
                    'checkIn' => $a->check_in,
                    'checkOut' => $a->check_out,
                    'observation' => $a->observation,
                ]),
            ],
        ];
    }
}
