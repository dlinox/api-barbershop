<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Group;

use App\Models\Academy\Material;
use App\Common\Helpers\DateHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentGroupItemResource extends JsonResource
{
    public function toArray($request)
    {
        $daysOfWeek = DateHelper::getDayNamesFromCsv($this->days_of_week);

        $paymentPlans = $this->paymentPlans;

        $payments = $paymentPlans->map(function ($paymentPlan) {
            return [
                'id' => null,
                'planId' => $paymentPlan->id,
                'type' => $paymentPlan->type,
                'status' => 'Pendiente',
                'startDate' => DateHelper::formatDate($paymentPlan->start_date),
                'endDate' => DateHelper::formatDate($paymentPlan->end_date),
                'subtotal' => (float)$paymentPlan->amount,
                'discount' => 0,
                'total' => (float)$paymentPlan->amount,
            ];
        });

        $payments = $payments->sortBy(fn($p) => [$p['type'] === 'enrollment' ? 0 : 1, $p['startDate']])->values();

        $materials = Material::select(
            'academy_materials.id',
            'inventory_products.name',
            'inventory_product_presentations.name as presentation_name',
            'inventory_product_presentations.unit_type as presentation_unit_type',
            'inventory_product_presentations.quantity as presentation_quantity',
            'academy_materials.quantity as material_quantity'
        )
            ->join('inventory_product_presentations', 'academy_materials.presentation_id', '=', 'inventory_product_presentations.id')
            ->join('inventory_products', 'inventory_product_presentations.product_id', '=', 'inventory_products.id')
            ->where('academy_materials.is_active', true)
            ->get()->map(function ($material) {
                return [
                    'value' => $material->id,
                    'title' => '[' . $material->material_quantity . '] ' . $material->name . ' ' . $material->presentation_name  . ' (' . $material->presentation_unit_type . 'x' . $material->presentation_quantity . ')',
                ];
            });

        return [
            'value' => $this->id,
            'title' => $this->name,
            'meta' => [
                'name' => $this->name,
                'startDate' => DateHelper::formatDate($this->start_date),
                'endDate' => DateHelper::formatDate($this->end_date),
                'daysOfWeek' =>  $daysOfWeek,
                'enrollmentPrice' => $this->enrollment_price,
                'monthlyPrice' => $this->monthly_price,
                'branch' => [
                    'name' => $this->branch_name,
                ],
                'room' => [
                    'number' => $this->room_number,
                ],
                'level' => [
                    'name' => $this->level_name,
                ],
                'schedule' => [
                    'shift' => $this->schedule_shift,
                    'startTime' => DateHelper::formatTime($this->schedule_start_time),
                    'endTime' => DateHelper::formatTime($this->schedule_end_time),
                ],
                'payments' => $payments,
                'materials' => $materials
            ],
        ];
    }
}
