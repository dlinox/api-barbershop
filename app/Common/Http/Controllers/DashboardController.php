<?php

namespace App\Common\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use App\Models\Academy\Attendance;
use App\Models\Academy\Branch as AcademyBranch;
use App\Models\Academy\Enrollment;
use App\Models\Academy\Group;
use App\Models\Academy\TeacherAttendance;
use App\Models\Barbershop\Branch as BarbershopBranch;
use App\Models\Barbershop\Reservation;
use App\Models\Barbershop\Service;
use App\Models\Barbershop\Ticket;
use App\Models\Barbershop\TicketService;
use App\Models\Core\Person;
use App\Models\Inventory\PurchaseOrder;
use App\Models\Inventory\Sale;
use App\Models\Inventory\Stock;
use App\Models\Profile\Barber;
use App\Models\Profile\Client;
use App\Models\Profile\Student;
use App\Models\Profile\Teacher;
use App\Models\Profile\Worker;
use App\Models\Treasury\CashSession;
use App\Models\Treasury\EmployeeAdvance;
use App\Models\Treasury\EmployeePayment;
use App\Models\Treasury\Expense;
use App\Models\Treasury\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController
{
    public function summary(Request $request)
    {
        [$from, $to] = $this->getDateRange($request);

        return ApiResponse::success([
            'barbershop' => $this->barbershopStats($from, $to),
            'academy' => $this->academyStats($from, $to),
            'inventory' => $this->inventoryStats($from, $to),
            'treasury' => $this->treasuryStats($from, $to),
            'staff' => $this->staffStats(),
        ]);
    }

    public function revenueChart(Request $request)
    {
        [$from, $to] = $this->getDateRange($request);
        $groupBy = $request->input('group_by', 'month'); // day, week, month

        $format = match ($groupBy) {
            'day' => '%Y-%m-%d',
            'week' => '%x-W%v',
            default => '%Y-%m',
        };

        $ticketRevenue = Ticket::where('status', 'confirmed')
            ->whereBetween('ticket_date', [$from, $to])
            ->select(DB::raw("DATE_FORMAT(ticket_date, '{$format}') as period"), DB::raw('SUM(total) as total'))
            ->groupBy(DB::raw("DATE_FORMAT(ticket_date, '{$format}')"))
            ->orderBy('period')
            ->pluck('total', 'period');

        $academyRevenue = Income::where('status', 'completed')
            ->where('transactionable_type', 'academy_enrollments')
            ->whereBetween('transaction_date', [$from, $to])
            ->select(DB::raw("DATE_FORMAT(transaction_date, '{$format}') as period"), DB::raw('SUM(total) as total'))
            ->groupBy(DB::raw("DATE_FORMAT(transaction_date, '{$format}')"))
            ->orderBy('period')
            ->pluck('total', 'period');

        $saleRevenue = Sale::where('status', 'completed')
            ->whereBetween('created_at', [$from, $to])
            ->select(DB::raw("DATE_FORMAT(created_at, '{$format}') as period"), DB::raw('SUM(total) as total'))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '{$format}')"))
            ->orderBy('period')
            ->pluck('total', 'period');

        $allPeriods = $this->generatePeriods($from, $to, $groupBy);

        return ApiResponse::success([
            'categories' => $allPeriods,
            'series' => [
                ['name' => 'Barbería', 'data' => $allPeriods->map(fn($p) => (float) ($ticketRevenue[$p] ?? 0))->values()],
                ['name' => 'Academia', 'data' => $allPeriods->map(fn($p) => (float) ($academyRevenue[$p] ?? 0))->values()],
                ['name' => 'Ventas', 'data' => $allPeriods->map(fn($p) => (float) ($saleRevenue[$p] ?? 0))->values()],
            ],
        ]);
    }

    public function ticketsByBarber(Request $request)
    {
        [$from, $to] = $this->getDateRange($request);

        $data = Ticket::where('barbershop_tickets.status', 'confirmed')
            ->whereBetween('ticket_date', [$from, $to])
            ->join('core_persons', 'barbershop_tickets.profile_barber_id', '=', 'core_persons.id')
            ->select(
                DB::raw("CONCAT(core_persons.name, ' ', core_persons.paternal_surname) as barber"),
                DB::raw('COUNT(*) as tickets'),
                DB::raw('SUM(barbershop_tickets.total) as revenue')
            )
            ->groupBy('barbershop_tickets.profile_barber_id', 'core_persons.name', 'core_persons.paternal_surname')
            ->orderByDesc('revenue')
            ->get();

        return ApiResponse::success($data);
    }

    public function topServices(Request $request)
    {
        [$from, $to] = $this->getDateRange($request);
        $limit = $request->input('limit', 10);

        $data = TicketService::join('barbershop_tickets', 'barbershop_ticket_services.ticket_id', '=', 'barbershop_tickets.id')
            ->join('barbershop_services', 'barbershop_ticket_services.service_id', '=', 'barbershop_services.id')
            ->where('barbershop_tickets.status', 'confirmed')
            ->whereBetween('barbershop_tickets.ticket_date', [$from, $to])
            ->select(
                'barbershop_services.name as service',
                DB::raw('SUM(barbershop_ticket_services.quantity) as quantity'),
                DB::raw('SUM(barbershop_ticket_services.amount) as revenue')
            )
            ->groupBy('barbershop_ticket_services.service_id', 'barbershop_services.name')
            ->orderByDesc('quantity')
            ->limit($limit)
            ->get();

        return ApiResponse::success($data);
    }

    public function enrollmentsByGroup(Request $request)
    {
        [$from, $to] = $this->getDateRange($request);

        $data = Enrollment::whereBetween('date', [$from, $to])
            ->join('academy_groups', 'academy_enrollments.group_id', '=', 'academy_groups.id')
            ->join('academy_levels', 'academy_groups.level_id', '=', 'academy_levels.id')
            ->select(
                'academy_groups.name as group_name',
                'academy_levels.name as level',
                DB::raw('COUNT(*) as enrollments'),
                DB::raw("SUM(CASE WHEN academy_enrollments.status = 'active' THEN 1 ELSE 0 END) as active"),
                DB::raw("SUM(CASE WHEN academy_enrollments.status = 'cancelled' THEN 1 ELSE 0 END) as cancelled")
            )
            ->groupBy('academy_enrollments.group_id', 'academy_groups.name', 'academy_levels.name')
            ->orderByDesc('enrollments')
            ->get();

        return ApiResponse::success($data);
    }

    public function attendanceOverview(Request $request)
    {
        [$from, $to] = $this->getDateRange($request);

        $studentAttendance = Attendance::join('academy_attendance_deadlines', 'academy_attendances.attendance_deadline_id', '=', 'academy_attendance_deadlines.id')
            ->whereBetween('academy_attendance_deadlines.date', [$from, $to])
            ->select('academy_attendances.status', DB::raw('COUNT(*) as count'))
            ->groupBy('academy_attendances.status')
            ->pluck('count', 'status');

        $teacherAttendance = TeacherAttendance::whereBetween('date', [$from, $to])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        return ApiResponse::success([
            'students' => [
                'present' => (int) ($studentAttendance['present'] ?? 0),
                'absent' => (int) ($studentAttendance['absent'] ?? 0),
                'late' => (int) ($studentAttendance['late'] ?? 0),
                'justified' => (int) (($studentAttendance['absent_justified'] ?? 0) + ($studentAttendance['late_justified'] ?? 0)),
            ],
            'teachers' => [
                'present' => (int) ($teacherAttendance['present'] ?? 0),
                'absent' => (int) ($teacherAttendance['absent'] ?? 0),
                'late' => (int) ($teacherAttendance['late'] ?? 0),
                'justified' => (int) (($teacherAttendance['absent_justified'] ?? 0) + ($teacherAttendance['late_justified'] ?? 0)),
            ],
        ]);
    }

    public function cashFlowChart(Request $request)
    {
        [$from, $to] = $this->getDateRange($request);

        $incomes = Income::where('status', 'completed')
            ->whereBetween('transaction_date', [$from, $to])
            ->select(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m') as period"), DB::raw('SUM(total) as total'))
            ->groupBy(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m')"))
            ->orderBy('period')
            ->pluck('total', 'period');

        $expenses = Expense::approved()->whereBetween('transaction_date', [$from, $to])
            ->select(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m') as period"), DB::raw('SUM(amount) as total'))
            ->groupBy(DB::raw("DATE_FORMAT(transaction_date, '%Y-%m')"))
            ->orderBy('period')
            ->pluck('total', 'period');

        $payments = EmployeePayment::where('status', 'paid')
            ->whereBetween('payment_date', [$from, $to])
            ->select(DB::raw("DATE_FORMAT(payment_date, '%Y-%m') as period"), DB::raw('SUM(total_amount) as total'))
            ->groupBy(DB::raw("DATE_FORMAT(payment_date, '%Y-%m')"))
            ->orderBy('period')
            ->pluck('total', 'period');

        $allPeriods = collect($incomes->keys())
            ->merge($expenses->keys())
            ->merge($payments->keys())
            ->unique()
            ->sort()
            ->values();

        return ApiResponse::success([
            'categories' => $allPeriods,
            'series' => [
                ['name' => 'Ingresos', 'data' => $allPeriods->map(fn($p) => (float) ($incomes[$p] ?? 0))->values()],
                ['name' => 'Gastos', 'data' => $allPeriods->map(fn($p) => (float) ($expenses[$p] ?? 0))->values()],
                ['name' => 'Pagos a empleados', 'data' => $allPeriods->map(fn($p) => (float) ($payments[$p] ?? 0))->values()],
            ],
        ]);
    }

    public function lowStockAlerts(Request $request)
    {
        $limit = $request->input('limit', 15);

        $data = Stock::join('inventory_product_presentations', 'inventory_stocks.presentation_id', '=', 'inventory_product_presentations.id')
            ->join('inventory_products', 'inventory_stocks.product_id', '=', 'inventory_products.id')
            ->whereColumn('inventory_stocks.current_stock', '<=', 'inventory_product_presentations.min_stock')
            ->where('inventory_product_presentations.is_active', true)
            ->select(
                'inventory_products.name as product',
                'inventory_product_presentations.name as presentation',
                'inventory_product_presentations.sku',
                'inventory_stocks.current_stock',
                'inventory_product_presentations.min_stock',
                'inventory_product_presentations.max_stock'
            )
            ->orderByRaw('inventory_stocks.current_stock - inventory_product_presentations.min_stock ASC')
            ->limit($limit)
            ->get();

        return ApiResponse::success($data);
    }

    public function recentTickets(Request $request)
    {
        $limit = $request->input('limit', 10);

        $data = Ticket::with(['barber.person:id,name,paternal_surname', 'client.person:id,name,paternal_surname', 'branch:id,name'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(fn($t) => [
                'id' => $t->id,
                'branch' => $t->branch?->name,
                'barber' => $t->barber?->person?->full_name,
                'client' => $t->client?->person?->full_name,
                'total' => $t->total,
                'status' => $t->status,
                'date' => $t->ticket_date,
            ]);

        return ApiResponse::success($data);
    }

    public function reservationsByStatus(Request $request)
    {
        [$from, $to] = $this->getDateRange($request);

        $data = Reservation::whereBetween('date', [$from, $to])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        return ApiResponse::success([
            'pending' => (int) ($data['pending'] ?? 0),
            'confirmed' => (int) ($data['confirmed'] ?? 0),
            'cancelled' => (int) ($data['cancelled'] ?? 0),
        ]);
    }

    public function payrollSummary(Request $request)
    {
        [$from, $to] = $this->getDateRange($request);

        $payments = EmployeePayment::where('status', 'paid')
            ->whereBetween('payment_date', [$from, $to])
            ->select('employee_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('employee_type')
            ->get()
            ->keyBy('employee_type');

        $advances = EmployeeAdvance::whereBetween('advance_date', [$from, $to])
            ->select('employee_type', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
            ->groupBy('employee_type')
            ->get()
            ->keyBy('employee_type');

        $types = ['profile_barbers', 'profile_teachers', 'profile_workers'];

        return ApiResponse::success([
            'payments' => collect($types)->mapWithKeys(fn($type) => [
                $type => [
                    'count' => (int) ($payments[$type]?->count ?? 0),
                    'total' => (float) ($payments[$type]?->total ?? 0),
                ],
            ]),
            'advances' => collect($types)->mapWithKeys(fn($type) => [
                $type => [
                    'count' => (int) ($advances[$type]?->count ?? 0),
                    'total' => (float) ($advances[$type]?->total ?? 0),
                ],
            ]),
        ]);
    }

    // ─── Private Helpers ─────────────────────────────────────

    private function getDateRange(Request $request): array
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to = $request->input('to', now()->toDateString()) . ' 23:59:59';

        return [$from, $to];
    }

    private function generatePeriods(string $from, string $to, string $groupBy): \Illuminate\Support\Collection
    {
        $start = \Carbon\Carbon::parse($from);
        $end = \Carbon\Carbon::parse($to);
        $periods = collect();

        return match ($groupBy) {
            'day' => (function () use ($start, $end, $periods) {
                while ($start->lte($end)) {
                    $periods->push($start->format('Y-m-d'));
                    $start->addDay();
                }
                return $periods;
            })(),
            'week' => (function () use ($start, $end, $periods) {
                $start->startOfWeek();
                while ($start->lte($end)) {
                    $periods->push($start->format('o-\\WW'));
                    $start->addWeek();
                }
                return $periods;
            })(),
            default => (function () use ($start, $end, $periods) {
                $start->startOfMonth();
                while ($start->lte($end)) {
                    $periods->push($start->format('Y-m'));
                    $start->addMonth();
                }
                return $periods;
            })(),
        };
    }

    private function barbershopStats(string $from, string $to): array
    {
        $tickets = Ticket::whereBetween('ticket_date', [$from, $to]);
        $confirmedTickets = (clone $tickets)->where('status', 'confirmed');

        return [
            'branches' => BarbershopBranch::where('is_active', true)->count(),
            'barbers' => Barber::where('is_active', true)->count(),
            'clients' => Client::count(),
            'services' => Service::where('is_active', true)->count(),
            'tickets_count' => $confirmedTickets->count(),
            'tickets_revenue' => (float) $confirmedTickets->sum('total'),
            'reservations_pending' => Reservation::where('status', 'pending')->whereBetween('date', [$from, $to])->count(),
            'avg_ticket' => (float) ($confirmedTickets->count() > 0 ? $confirmedTickets->avg('total') : 0),
        ];
    }

    private function academyStats(string $from, string $to): array
    {
        return [
            'branches' => AcademyBranch::where('is_active', true)->count(),
            'groups_active' => Group::where('is_active', true)->count(),
            'students_active' => Enrollment::where('status', 'active')->distinct('profile_student_id')->count('profile_student_id'),
            'teachers' => Teacher::where('is_active', true)->count(),
            'enrollments_period' => Enrollment::whereBetween('date', [$from, $to])->count(),
            'enrollments_active' => Enrollment::where('status', 'active')->count(),
            'enrollments_cancelled' => Enrollment::where('status', 'cancelled')->count(),
        ];
    }

    private function inventoryStats(string $from, string $to): array
    {
        return [
            'low_stock_count' => Stock::join('inventory_product_presentations', 'inventory_stocks.presentation_id', '=', 'inventory_product_presentations.id')
                ->whereColumn('inventory_stocks.current_stock', '<=', 'inventory_product_presentations.min_stock')
                ->where('inventory_product_presentations.is_active', true)
                ->count(),
            'purchase_orders_pending' => PurchaseOrder::where('status', 'pending')->count(),
            'sales_count' => Sale::where('status', 'completed')->whereBetween('created_at', [$from, $to])->count(),
            'sales_revenue' => (float) Sale::where('status', 'completed')->whereBetween('created_at', [$from, $to])->sum('total'),
        ];
    }

    private function treasuryStats(string $from, string $to): array
    {
        $sessions = CashSession::where('status', 'open');
        $incomes = Income::where('status', 'completed')->whereBetween('transaction_date', [$from, $to]);
        $expenses = Expense::approved()->whereBetween('transaction_date', [$from, $to]);

        return [
            'open_sessions' => $sessions->count(),
            'total_income' => (float) $incomes->sum('total'),
            'total_expenses' => (float) $expenses->sum('amount'),
            'net_income' => (float) ($incomes->sum('total') - $expenses->sum('amount')),
            'pending_advances' => EmployeeAdvance::where('status', 'pending')->count(),
            'total_employee_payments' => (float) EmployeePayment::where('status', 'paid')->whereBetween('payment_date', [$from, $to])->sum('total_amount'),
        ];
    }

    private function staffStats(): array
    {
        return [
            'barbers' => Barber::where('is_active', true)->count(),
            'teachers' => Teacher::where('is_active', true)->count(),
            'workers' => Worker::where('is_active', true)->count(),
            'total' => Barber::where('is_active', true)->count() + Teacher::where('is_active', true)->count() + Worker::where('is_active', true)->count(),
        ];
    }
}
