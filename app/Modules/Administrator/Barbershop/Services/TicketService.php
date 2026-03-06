<?php

namespace App\Modules\Administrator\Barbershop\Services;

use App\Modules\Administrator\Barbershop\Repositories\TicketRepository;
use App\Modules\Administrator\Barbershop\Repositories\Actions\CreateTicketAction;
use App\Modules\Administrator\Barbershop\Repositories\Actions\ConfirmTicketAction;
use App\Modules\Administrator\Barbershop\Repositories\Actions\CancelTicketAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketService
{
    public function __construct(
        private TicketRepository $ticketRepository,
        private CreateTicketAction $createTicketAction,
        private ConfirmTicketAction $confirmTicketAction,
        private CancelTicketAction $cancelTicketAction,
    ) {}

    public function dataTable(Request $request, int $branchId)
    {
        return $this->ticketRepository->dataTable($request, $branchId);
    }

    public function findById(int $id)
    {
        return $this->ticketRepository->findById($id);
    }

    /**
     * Crea/actualiza un ticket.
     * Si se envía income, también lo confirma (completa venta, kardex/stock, income).
     */
    public function save(array $data): void
    {
        DB::beginTransaction();
        try {
            // 1. Crear o actualizar ticket + servicios + venta pendiente
            $ticket = $this->createTicketAction->execute($data);

            // 2. Si se envía income → confirmar
            if (!empty($data['income'])) {
                $ticket->load(['services', 'sale.items']);
                $infrastructureId = $ticket->branch->getInfrastructureId();

                $this->confirmTicketAction->execute($ticket, $data, $infrastructureId);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function cancel(int $ticketId): void
    {
        DB::beginTransaction();
        try {
            $this->cancelTicketAction->execute($ticketId);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
