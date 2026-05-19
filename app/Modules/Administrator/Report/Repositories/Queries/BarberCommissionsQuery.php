<?php

namespace App\Modules\Administrator\Report\Repositories\Queries;

use App\Models\Barbershop\Branch;
use App\Models\Barbershop\Ticket;
use App\Models\Core\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BarberCommissionsQuery
{
    public function __invoke(int $branchId, string $dateFrom, string $dateTo): array
    {
        $branch = Branch::findOrFail($branchId);

        $barbers = DB::table('barbershop_tickets as t')
            ->join('profile_barbers as b', 'b.id', '=', 't.profile_barber_id')
            ->join('core_persons as p', 'p.id', '=', 'b.id')
            ->join('barbershop_ticket_services as ts', 'ts.ticket_id', '=', 't.id')
            ->join('barbershop_services as s', 's.id', '=', 'ts.service_id')
            ->where('t.branch_id', $branchId)
            ->where('t.status', 'confirmed')
            ->whereBetween('t.ticket_date', [$dateFrom, $dateTo . ' 23:59:59'])
            ->select(
                'b.id as barber_id',
                DB::raw("CONCAT(p.name, ' ', COALESCE(p.paternal_surname, ''), ' ', COALESCE(p.maternal_surname, '')) as barber_name"),
                'b.commission_percentage',
                DB::raw('COUNT(DISTINCT t.id) as ticket_count'),
                DB::raw('SUM(ts.amount - ts.discount) as total_services'),
            )
            ->groupBy('b.id', 'barber_name', 'b.commission_percentage')
            ->orderByDesc('total_services')
            ->get();

        $rows = [];
        $grandTotal = 0;
        $grandCommission = 0;

        foreach ($barbers as $barber) {
            $totalServices = (float) $barber->total_services;
            $commissionRate = (float) $barber->commission_percentage;
            $commissionAmount = round($totalServices * $commissionRate / 100, 2);

            $services = DB::table('barbershop_ticket_services as ts')
                ->join('barbershop_tickets as t', 't.id', '=', 'ts.ticket_id')
                ->join('barbershop_services as s', 's.id', '=', 'ts.service_id')
                ->where('t.branch_id', $branchId)
                ->where('t.status', 'confirmed')
                ->where('t.profile_barber_id', $barber->barber_id)
                ->whereBetween('t.ticket_date', [$dateFrom, $dateTo . ' 23:59:59'])
                ->select(
                    's.name as service_name',
                    DB::raw('SUM(ts.quantity) as quantity'),
                    DB::raw('SUM(ts.amount - ts.discount) as total'),
                )
                ->groupBy('s.name')
                ->orderByDesc('total')
                ->get()
                ->toArray();

            $rows[] = [
                'barber_name'        => trim($barber->barber_name),
                'commission_rate'    => $commissionRate,
                'ticket_count'       => (int) $barber->ticket_count,
                'total_services'     => $totalServices,
                'commission_amount'  => $commissionAmount,
                'services'           => $services,
            ];

            $grandTotal += $totalServices;
            $grandCommission += $commissionAmount;
        }

        return [
            'branch'    => $branch,
            'date_from' => $dateFrom,
            'date_to'   => $dateTo,
            'rows'      => $rows,
            'totals'    => [
                'services'   => $grandTotal,
                'commission' => $grandCommission,
            ],
        ];
    }

    public function toBladeData(array $queryData): array
    {
        $from = Carbon::parse($queryData['date_from'])->format('d/m/Y');
        $to = Carbon::parse($queryData['date_to'])->format('d/m/Y');

        return [
            'company'         => Company::first(),
            'report_title'    => 'REPORTE DE COMISIONES POR BARBERO',
            'report_subtitle' => 'BARBERÍA',
            'report_date'     => "{$from} - {$to}",
            'report_day'      => $queryData['branch']->name,
            'branch'          => $queryData['branch'],
            'branch_name'     => $queryData['branch']->name,
            'date_from'       => $from,
            'date_to'         => $to,
            'rows'            => $queryData['rows'],
            'total_services'  => $queryData['totals']['services'],
            'total_commission' => $queryData['totals']['commission'],
        ];
    }

    public function toReportData(array $queryData): array
    {
        return [
            'branch_id'        => $queryData['branch']->id,
            'branch_name'      => $queryData['branch']->name,
            'date_from'        => $queryData['date_from'],
            'date_to'          => $queryData['date_to'],
            'barber_count'     => count($queryData['rows']),
            'total_services'   => $queryData['totals']['services'],
            'total_commission' => $queryData['totals']['commission'],
        ];
    }
}
