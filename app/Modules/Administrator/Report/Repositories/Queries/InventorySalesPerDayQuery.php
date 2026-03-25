<?php

namespace App\Modules\Administrator\Report\Repositories\Queries;

use App\Models\Core\Company;
use App\Models\Core\Infrastructure;
use App\Models\Inventory\Sale;
use Carbon\Carbon;

class InventorySalesPerDayQuery
{
    public function __invoke(int $infrastructureId, string $date): array
    {
        $infrastructure = Infrastructure::findOrFail($infrastructureId);

        $sales = Sale::where('status', 'completed')
            ->whereDate('created_at', $date)
            ->where('infrastructure_id', $infrastructureId)
            ->with(['items.presentation.product', 'client'])
            ->orderBy('id')
            ->get();

        $rows = [];
        $total = 0;

        foreach ($sales as $sale) {
            $clientName = $sale->client?->full_name ?? 'Cliente general';
            $items = $sale->items->map(fn($i) => ($i->presentation?->product?->name ?? '---') . ' x' . $i->quantity)->implode(', ');

            $rows[] = [
                'sale_number'  => $sale->sale_number ?? $sale->id,
                'client'       => $clientName,
                'items'        => $items,
                'items_count'  => $sale->items->sum('quantity'),
                'discount'     => (float) $sale->discount,
                'total'        => (float) $sale->total,
            ];
            $total += (float) $sale->total;
        }

        return [
            'infrastructure' => $infrastructure,
            'date'           => $date,
            'rows'           => $rows,
            'total'          => $total,
        ];
    }

    public function toBladeData(array $queryData): array
    {
        $parsedDate = Carbon::parse($queryData['date']);
        $dayNames = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

        return [
            'company'             => Company::first(),
            'report_title'        => 'VENTAS DEL DÍA',
            'report_subtitle'     => 'INVENTARIO',
            'report_date'         => $parsedDate->format('d/m/Y'),
            'report_day'          => $dayNames[$parsedDate->dayOfWeek],
            'infrastructure_name' => $queryData['infrastructure']->name,
            'rows'                => $queryData['rows'],
            'total'               => $queryData['total'],
        ];
    }

    public function toReportData(array $queryData): array
    {
        return [
            'infrastructure_id'   => $queryData['infrastructure']->id,
            'infrastructure_name' => $queryData['infrastructure']->name,
            'date'                => $queryData['date'],
            'total'               => $queryData['total'],
            'rows_count'          => count($queryData['rows']),
        ];
    }
}
