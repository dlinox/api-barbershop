<?php

namespace App\Modules\Administrator\Report\Repositories\Queries;

use App\Models\Reports\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReportDataTableQuery
{
    public function __invoke(Request $request, string $type): array
    {
        $perPage = $request->input('perPage', 10);
        $perPage = $perPage == -1 ? 999999 : $perPage;

        $query = Report::where('type', $type)
            ->with('generatedBy');

        // ─── Filtros por campos JSON (data->) ───
        $filters = $request->input('filters', []);
        foreach ($filters as $key => $value) {
            if (is_null($value)) continue;

            $snakeKey = Str::snake($key);
            $query->where("data->{$snakeKey}", $value);
        }

        // ─── Rangos (date range) ───
        $ranges = $request->input('ranges', []);
        foreach ($ranges as $key => $range) {
            $snakeKey = Str::snake($key);
            $from = $range['from'] ?? null;
            $to = $range['to'] ?? null;

            if ($from && $to) {
                $query->whereBetween("data->{$snakeKey}", [$from, $to]);
            } elseif ($from) {
                $query->where("data->{$snakeKey}", '>=', $from);
            } elseif ($to) {
                $query->where("data->{$snakeKey}", '<=', $to);
            }
        }

        // ─── Búsqueda ───
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('data->branch_name', 'LIKE', "%{$search}%")
                  ->orWhere('data->worker_name', 'LIKE', "%{$search}%")
                  ->orWhere('data->group_name', 'LIKE', "%{$search}%")
                  ->orWhere('data->infrastructure_name', 'LIKE', "%{$search}%")
                  ->orWhere('data->cash_register_name', 'LIKE', "%{$search}%")
                  ->orWhere('data->product_name', 'LIKE', "%{$search}%")
                  ->orWhere('data->sku', 'LIKE', "%{$search}%")
                  ->orWhere('data->register_name', 'LIKE', "%{$search}%");
            });
        }

        // ─── Ordenamiento ───
        $sortBy = $request->input('sortBy', []);
        if (!empty($sortBy)) {
            foreach ($sortBy as $sort) {
                $key = Str::snake($sort['key']);
                $order = $sort['order'] ?? 'asc';

                $jsonSortableKeys = [
                    // Academy
                    'date', 'branch_name', 'worker_name', 'group_name', 'month', 'year',
                    'total_cash', 'total_bank', 'total_day', 'student_count', 'attendance_rate',
                    // Barbershop
                    'date_from', 'date_to', 'barber_count', 'total_services', 'total_commission',
                    'infrastructure_name', 'cash_register_name', 'total_incomes', 'total_expenses',
                    'opening_amount', 'expected_closing', 'actual_closing', 'difference',
                    // Treasury
                    'total', 'rows_count', 'total_income', 'total_expense', 'net_profit',
                    'register_name',
                    // Inventory
                    'product_name', 'sku', 'movements_count', 'final_balance',
                    'products_count', 'total_value', 'total_deficit', 'total_restock_cost',
                ];

                if (in_array($key, $jsonSortableKeys)) {
                    $query->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(data, '$.{$key}')) {$order}");
                } else {
                    $query->orderBy($key, $order);
                }
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $items = $query->paginate($perPage);

        return [
            'data'        => $items->getCollection(),
            'currentPage' => $items->currentPage(),
            'perPage'     => $items->perPage(),
            'total'       => $items->total(),
        ];
    }
}
