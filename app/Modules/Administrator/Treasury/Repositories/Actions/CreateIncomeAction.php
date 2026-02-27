<?php

namespace App\Modules\Administrator\Treasury\Repositories\Actions;

use App\Common\Exceptions\ApiException;
use App\Models\Treasury\Income;

class CreateIncomeAction
{
    /**
     * Crea un ingreso completo: cabecera + detalles + métodos de pago.
     *
     * @param array $data    Datos del income (receipt_type, receipt_serie, observations, client_id, details, payment_methods)
     * @param int   $infrastructureId  ID de la infraestructura (sucursal)
     * @param string|null $transactionableType  Clase del origen polimórfico (ej: EnrollmentPayment::class)
     * @param int|null    $transactionableId    ID del origen polimórfico
     * @return Income
     */
    public function execute(
        array $data,
        int $infrastructureId,
        ?string $transactionableType = null,
        ?int $transactionableId = null,
    ): Income {

        // ─── Calcular totales desde los detalles ───
        $subtotal = 0;
        $totalDiscount = 0;

        foreach ($data['details'] as &$detail) {
            $lineSubtotal = ($detail['unit_price'] * $detail['quantity']) - ($detail['discount'] ?? 0);
            $detail['subtotal'] = $lineSubtotal;
            $subtotal += $lineSubtotal;
            $totalDiscount += $detail['discount'] ?? 0;
        }
        unset($detail);

        $total = $subtotal;

        // ─── Validar que los pagos cubran el total ───
        $totalPaid = collect($data['payment_methods'])->sum('amount');

        if (round($totalPaid, 2) !== round($total, 2)) {
            throw new ApiException(
                "El monto pagado (S/ {$totalPaid}) no coincide con el total del ingreso (S/ {$total})."
            );
        }

        // ─── Crear cabecera del ingreso ───
        $income = Income::create([
            'infrastructure_id'    => $infrastructureId,
            'cash_session_id'      => $data['cash_register_id'] ?? null,
            'receipt_type'         => $data['receipt_type'],
            'receipt_serie'        => $data['receipt_serie'],
            'person_id'            => $data['client_id'] ?? null,
            'transactionable_type' => $transactionableType,
            'transactionable_id'   => $transactionableId,
            'subtotal'             => $subtotal,
            'discount'             => $totalDiscount,
            'tax'                  => 0,
            'total'                => $total,
            'observations'         => $data['observations'] ?? null,
            'status'               => 'completed',
        ]);

        // ─── Crear detalles (líneas del comprobante) ───
        foreach ($data['details'] as $detail) {
            $income->details()->create([
                'description' => $detail['description'],
                'quantity'    => $detail['quantity'],
                'unit_price'  => $detail['unit_price'],
                'discount'    => $detail['discount'] ?? 0,
                'subtotal'    => $detail['subtotal'],
            ]);
        }

        // ─── Crear métodos de pago (solo los que tengan monto > 0) ───
        foreach ($data['payment_methods'] as $paymentMethod) {
            if (($paymentMethod['amount'] ?? 0) <= 0) continue;

            $income->paymentMethods()->create([
                'payment_method_id' => $paymentMethod['id'],
                'amount'            => $paymentMethod['amount'],
                'payment_reference' => $paymentMethod['reference'] ?? null,
            ]);
        }

        return $income->load(['details', 'paymentMethods']);
    }
}
