<?php

namespace App\Modules\Administrator\Treasury\Repositories\Actions;

use App\Common\Exceptions\ApiException;
use App\Models\Academy\EnrollmentPayment;
use App\Models\Barbershop\Ticket;
use App\Models\Inventory\Sale;
use App\Models\Treasury\Income;
use App\Modules\Administrator\Inventory\Repositories\Actions\RegisterKardexMovementAction;
use Illuminate\Support\Facades\DB;

class AnnulIncomeAction
{
    public function __construct(
        private RegisterKardexMovementAction $registerKardexMovementAction,
    ) {}

    public function execute(int $incomeId): Income
    {
        $income = Income::findOrFail($incomeId);

        if ($income->status === 'cancelled') {
            throw new ApiException('El ingreso ya fue anulado.');
        }

        DB::beginTransaction();
        try {
            match ($income->transactionable_type) {
                'inventory_sales'             => $this->annulSale($income->transactionable_id),
                'barbershop_tickets'          => $this->annulTicket($income->transactionable_id),
                'academy_enrollment_payments' => $this->annulEnrollmentPayment($income->transactionable_id),
                default                       => null,
            };

            $income->update(['status' => 'cancelled']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return $income;
    }

    private function annulSale(int $saleId): void
    {
        $sale = Sale::with('items.presentation')->find($saleId);

        if (!$sale || $sale->status === 'cancelled') return;

        if ($sale->status === 'completed') {
            foreach ($sale->items as $item) {
                $this->registerKardexMovementAction->execute([
                    'product_id'        => $item->presentation->product_id,
                    'presentation_id'   => $item->presentation_id,
                    'infrastructure_id' => $sale->infrastructure_id,
                    'quantity'          => $item->quantity,
                    'unit_cost'         => $item->unit_price,
                    'movement_type'     => 'in',
                    'reason'            => 'return',
                    'reference_id'      => $sale->id,
                    'reference_type'    => Sale::class,
                    'notes'             => 'Devolución por anulación de ingreso',
                ]);
            }
        }

        $sale->update(['status' => 'cancelled']);
    }

    private function annulTicket(int $ticketId): void
    {
        $ticket = Ticket::with(['sale.items.presentation'])->find($ticketId);

        if (!$ticket || $ticket->status === 'cancelled') return;

        $sale = $ticket->sale;

        if ($ticket->status === 'confirmed' && $sale && $sale->status === 'completed') {
            $branch           = $ticket->branch;
            $infrastructureId = $branch->getInfrastructureId();

            foreach ($sale->items as $item) {
                $this->registerKardexMovementAction->execute([
                    'product_id'        => $item->presentation->product_id,
                    'presentation_id'   => $item->presentation_id,
                    'infrastructure_id' => $infrastructureId,
                    'quantity'          => $item->quantity,
                    'unit_cost'         => $item->unit_price,
                    'movement_type'     => 'in',
                    'reason'            => 'return',
                    'reference_id'      => $sale->id,
                    'reference_type'    => Sale::class,
                    'notes'             => 'Devolución por anulación de ingreso - ticket #' . $ticket->id,
                ]);
            }

            $sale->update(['status' => 'cancelled']);
        }

        if ($sale && $sale->status === 'pending') {
            $sale->update(['status' => 'cancelled']);
        }

        $ticket->update(['status' => 'cancelled']);
    }

    private function annulEnrollmentPayment(int $enrollmentPaymentId): void
    {
        $enrollmentPayment = EnrollmentPayment::find($enrollmentPaymentId);

        if (!$enrollmentPayment || $enrollmentPayment->status === 'cancelled') return;

        $enrollmentPayment->update(['status' => 'cancelled']);
    }
}
