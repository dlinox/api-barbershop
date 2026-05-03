<?php

namespace App\Modules\BarbershopPanel\Barbershop\Services;

use App\Modules\BarbershopPanel\Barbershop\Repositories\TicketRepository;
use App\Modules\Administrator\Barbershop\Repositories\Actions\CreateTicketAction;
use App\Modules\Administrator\Barbershop\Repositories\Actions\ConfirmTicketAction;
use App\Modules\Administrator\Barbershop\Repositories\Actions\CancelTicketAction;
use App\Modules\Administrator\Inventory\Repositories\Actions\EnsureClientProfileAction;
use App\Common\Exceptions\ApiException;
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

    public function ticketsOverview(int $cashSessionId): array
    {
        return $this->ticketRepository->ticketsOverview($cashSessionId);
    }

    public function waitingQueue(int $cashSessionId)
    {
        return $this->ticketRepository->waitingQueue($cashSessionId);
    }

    public function save(array $data): array
    {
        $income = null;

        DB::beginTransaction();
        try {
            if (!empty($data['client_id'])) {
                $this->ensureClientProfileAction->execute($data['client_id']);
            }

            $ticket = $this->createTicketAction->execute($data);

            if (!empty($data['income'])) {
                if (empty($data['barber_id'])) {
                    throw new ApiException('Debe asignar un barbero antes de confirmar el ticket.');
                }

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

    public function cancel(int $id): void
    {
        DB::beginTransaction();
        try {
            $this->cancelTicketAction->execute($id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}