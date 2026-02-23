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

        $payments = $payments->sortBy('type')->sortBy('startDate')->values();

        $materials = Material::select('academy_materials.id', 'inventory_products.name', 'academy_materials.quantity')
            ->join('inventory_products', 'academy_materials.product_id', '=', 'inventory_products.id')
            ->where('academy_materials.is_active', true)
            ->get()->map(function ($material) {
                return [
                    'value' => $material->id,
                    'title' => $material->name . ' (Cantidad: ' . $material->quantity . ')',
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
