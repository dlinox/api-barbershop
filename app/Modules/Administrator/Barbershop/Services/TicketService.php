<?php

namespace App\Modules\Administrator\Barbershop\Services;

use App\Modules\Administrator\Barbershop\Repositories\TicketRepository;
use App\Modules\Administrator\Barbershop\Repositories\Actions\CreateTicketAction;
use App\Modules\Administrator\Barbershop\Repositories\Actions\ConfirmTicketAction;
use App\Modules\Administrator\Barbershop\Repositories\Actions\CancelTicketAction;
use App\Modules\Administrator\Inventory\Repositories\Actions\EnsureClientProfileAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketService
{
    public function __construct(
        private TicketRepository $ticketRepository,
        private CreateTicketAction $createTicketAction,
        private ConfirmTicketAction $confirmTicketAction,
        private CancelTicketAction $cancelTicketAction,
        private EnsureClientProfileAction $ensureClientProfileAction,
    ) {}

    public function dataTable(Request $request)
    {
        return $this->ticketRepository->dataTable($request);
    }

    public function findById(int $id)
    {
        return $this->ticketRepository->findById($id);
    }

    /**
     * Crea/actualiza un ticket.
     * Si se envía income, también lo confirma (completa venta, kardex/stock, income).
     */
    public function save(array $data): array
    {
        $income = null;

        DB::beginTransaction();
        try {
            // 0. Asegurar perfil de cliente si se indicó
            if (!empty($data['client_id'])) {
                $this->ensureClientProfileAction->execute($data['client_id']);
            }

            // 1. Crear o actualizar ticket + servicios + venta pendiente
            $ticket = $this->createTicketAction->execute($data);

            // 2. Si se envía income → confirmar
            if (!empty($data['income'])) {
                $ticket->load(['services', 'sale.items']);
                $infrastructureId = $ticket->branch->getInfrastructureId();

                [, $income] = $this->confirmTicketAction->execute($ticket, $data, $infrastructureId);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return [
            'incomeId' => $income?->id,
        ];
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
