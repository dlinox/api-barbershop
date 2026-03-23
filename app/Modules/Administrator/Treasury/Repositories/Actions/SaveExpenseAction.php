<?php

namespace App\Modules\Administrator\Treasury\Repositories\Actions;

use App\Models\Treasury\CashSession;
use App\Models\Treasury\Expense;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SaveExpenseAction
{
    /**
     * Crea o actualiza un gasto.
     * Reutilizable desde gastos de caja y gastos generales.
     */
    public function execute(array $data, ?string $defaultStatus = 'approved'): Expense
    {
        $data['status'] = $data['status'] ?? $defaultStatus;

        // Si viene de una sesión de caja, derivar infrastructure_id automáticamente
        if (!empty($data['cash_session_id']) && empty($data['infrastructure_id'])) {
            $session = CashSession::with('cashRegister')->find($data['cash_session_id']);
            if ($session?->cashRegister) {
                $data['infrastructure_id'] = $session->cashRegister->infrastructure_id;
            }
        }

        // Procesar imagen base64 del comprobante
        if (!empty($data['voucher_image'])) {
            $data['voucher_image_path'] = $this->storeBase64Image($data['voucher_image']);
        }
        unset($data['voucher_image']);

        return Expense::updateOrCreate(['id' => $data['id'] ?? null], $data);
    }

    private function storeBase64Image(string $base64): string
    {
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $decodedData = base64_decode($imageData);

        $extension = 'jpg';
        if (preg_match('/^data:image\/(\w+);base64,/', $base64, $matches)) {
            $extension = $matches[1];
        }

        $filename = 'expenses/' . Str::uuid() . '.' . $extension;
        Storage::disk('public')->put($filename, $decodedData);

        return $filename;
    }
}
