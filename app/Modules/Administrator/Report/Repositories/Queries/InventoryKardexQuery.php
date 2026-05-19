<?php

namespace App\Modules\Administrator\Report\Repositories\Queries;

use App\Models\Core\Company;
use App\Models\Core\Infrastructure;
use App\Models\Inventory\Kardex;
use App\Models\Inventory\ProductPresentation;
use Carbon\Carbon;

class InventoryKardexQuery
{
    public function __invoke(int $presentationId, int $infrastructureId, string $dateFrom, string $dateTo): array
    {
        $presentation = ProductPresentation::with('product')->findOrFail($presentationId);
        $infrastructure = Infrastructure::with('infrastructurable')->findOrFail($infrastructureId);

        $movements = Kardex::where('presentation_id', $presentationId)
            ->where('infrastructure_id', $infrastructureId)
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->orderBy('created_at')
            ->get();

        $rows = [];
        foreach ($movements as $m) {
            $rows[] = [
                'date'              => Carbon::parse($m->created_at)->format('d/m/Y H:i'),
                'movement_type'     => $m->movement_type === 'in' ? 'Entrada' : 'Salida',
                'reason'            => $m->reason ?? '---',
                'quantity'          => (int) $m->quantity,
                'unit_cost'         => (float) $m->unit_cost,
                'total_cost'        => (float) $m->total_cost,
                'balance_quantity'  => (int) $m->balance_quantity,
                'balance_unit_cost' => (float) $m->balance_unit_cost,
                'balance_total'     => (float) $m->balance_total_cost,
                'notes'             => $m->notes ?? '',
            ];
        }

        return [
            'presentation'   => $presentation,
            'infrastructure' => $infrastructure,
            'date_from'      => $dateFrom,
            'date_to'        => $dateTo,
            'rows'           => $rows,
        ];
    }

    public function toBladeData(array $queryData): array
    {
        $parsedFrom = Carbon::parse($queryData['date_from']);
        $parsedTo = Carbon::parse($queryData['date_to']);
        $product = $queryData['presentation']->product;

        return [
            'company'             => Company::first(),
            'infrastructure'      => $queryData['infrastructure'],
            'report_title'        => 'KARDEX DE PRODUCTO',
            'report_subtitle'     => 'INVENTARIO',
            'report_date'         => $parsedFrom->format('d/m/Y') . ' - ' . $parsedTo->format('d/m/Y'),
            'report_day'          => $parsedFrom->format('d/m/Y') . ' al ' . $parsedTo->format('d/m/Y'),
            'product_name'        => $product->name,
            'presentation_name'   => $queryData['presentation']->name ?? $queryData['presentation']->sku,
            'sku'                 => $queryData['presentation']->sku,
            'infrastructure_name' => $queryData['infrastructure']->name,
            'rows'                => $queryData['rows'],
        ];
    }

    public function toReportData(array $queryData): array
    {
        $lastRow = end($queryData['rows']);

        return [
            'presentation_id'    => $queryData['presentation']->id,
            'product_name'       => $queryData['presentation']->product->name,
            'sku'                => $queryData['presentation']->sku,
            'infrastructure_id'  => $queryData['infrastructure']->id,
            'infrastructure_name'=> $queryData['infrastructure']->name,
            'date_from'          => $queryData['date_from'],
            'date_to'            => $queryData['date_to'],
            'movements_count'    => count($queryData['rows']),
            'final_balance'      => $lastRow ? $lastRow['balance_quantity'] : 0,
        ];
    }
}
