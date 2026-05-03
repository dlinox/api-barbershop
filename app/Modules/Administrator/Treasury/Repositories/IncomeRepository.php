<?php

namespace App\Modules\Administrator\Treasury\Repositories;

use App\Models\Treasury\Income;
use App\Models\Treasury\IncomeAudit;
use App\Models\Treasury\IncomeDetail;
use App\Models\Treasury\IncomePaymentMethod;
use App\Common\Exceptions\ApiException;
use App\Common\Traits\HasInfrastructureScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeRepository
{
    use HasInfrastructureScope;

    public function dataTable(Request $request)
    {
        $query = Income::select(
            'treasury_incomes.*',
            'core_persons.name as person_name',
            'core_persons.paternal_surname as person_paternal_surname',
            'core_persons.maternal_surname as person_maternal_surname',
            'core_persons.document_number as person_document_number',
            'auth_users.username as user_username',
        )
            ->leftJoin('core_persons', 'core_persons.id', 'treasury_incomes.person_id')
            ->leftJoin('auth_users', 'auth_users.id', 'treasury_incomes.user_id');

        $this->scopeByInfrastructure($query, 'treasury_incomes.infrastructure_id');

        // if ($request->infrastructureId) {
        //     $query->where('treasury_incomes.infrastructure_id', $request->infrastructureId);
        // }

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('treasury_incomes.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function getById(int $id)
    {
        return Income::with(['details', 'paymentMethods.paymentMethod', 'person'])
            ->findOrFail($id);
    }

    public function update(int $id, array $data): Income
    {
        $income = Income::findOrFail($id);

        if ($income->status !== 'completed') {
            throw new ApiException('Solo se pueden editar ingresos con estado completado', 422);
        }

        // ─── Guardar snapshot antes de editar ───
        IncomeAudit::create([
            'income_id'                => $id,
            'user_id'                  => Auth::id(),
            'edit_description'         => $data['edit_description'],
            'subtotal_before'          => $income->subtotal,
            'discount_before'          => $income->discount,
            'total_before'             => $income->total,
            'details_snapshot'         => $income->details()
                ->get(['id', 'description', 'quantity', 'unit_price', 'discount', 'subtotal'])
                ->toArray(),
            'payment_methods_snapshot' => $income->paymentMethods()
                ->get(['id', 'payment_method_id', 'amount', 'payment_reference'])
                ->toArray(),
        ]);

        // ─── Actualizar detalles y recalcular totales ───
        $subtotal      = 0;
        $totalDiscount = 0;

        foreach ($data['details'] as $detailData) {
            $lineSubtotal = ($detailData['unit_price'] * $detailData['quantity']) - ($detailData['discount'] ?? 0);
            $subtotal      += $lineSubtotal;
            $totalDiscount += $detailData['discount'] ?? 0;

            IncomeDetail::where('id', $detailData['id'])
                ->where('income_id', $id)
                ->update([
                    'description' => $detailData['description'],
                    'quantity'    => $detailData['quantity'],
                    'unit_price'  => $detailData['unit_price'],
                    'discount'    => $detailData['discount'] ?? 0,
                    'subtotal'    => $lineSubtotal,
                ]);
        }

        $total = $subtotal;

        // ─── Validar que los pagos cubran el total ───
        $totalPaid = collect($data['payment_methods'])->sum('amount');

        if (round((float) $totalPaid, 2) !== round((float) $total, 2)) {
            throw new ApiException(
                "El monto pagado (S/ {$totalPaid}) no coincide con el total del ingreso (S/ {$total}).",
                422
            );
        }

        // ─── Sincronizar métodos de pago (delete + recreate) ───
        $income->paymentMethods()->delete();
        foreach ($data['payment_methods'] as $pmData) {
            if (($pmData['amount'] ?? 0) <= 0) continue;
            $income->paymentMethods()->create([
                'payment_method_id' => $pmData['payment_method_id'],
                'amount'            => $pmData['amount'],
                'payment_reference' => $pmData['payment_reference'] ?? null,
            ]);
        }

        // ─── Actualizar cabecera del ingreso ───
        $income->update([
            'subtotal'  => $subtotal,
            'discount'  => $totalDiscount,
            'total'     => $total,
            'is_edited' => true,
        ]);

        return $income->fresh();
    }

    public function getAudits(int $id)
    {
        return IncomeAudit::where('income_id', $id)
            ->join('auth_users', 'auth_users.id', '=', 'treasury_income_audits.user_id')
            ->select(
                'treasury_income_audits.id',
                'treasury_income_audits.edit_description',
                'treasury_income_audits.subtotal_before',
                'treasury_income_audits.discount_before',
                'treasury_income_audits.total_before',
                'treasury_income_audits.details_snapshot',
                'treasury_income_audits.payment_methods_snapshot',
                'treasury_income_audits.created_at',
                'auth_users.username as edited_by',
            )
            ->orderByDesc('treasury_income_audits.created_at')
            ->get();
    }

}
