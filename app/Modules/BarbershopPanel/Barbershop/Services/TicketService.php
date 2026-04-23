<?php

namespace App\Modules\BarbershopPanel\Barbershop\Services;

use App\Modules\BarbershopPanel\Barbershop\Repositories\TicketRepository;
use App\Modules\Administrator\Barbershop\Repositories\Actions\CreateTicketAction;
use App\Modules\Administrator\Barbershop\Repositories\Actions\CancelTicketAction;
use App\Modules\Administrator\Inventory\Repositories\Actions\EnsureClientProfileAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TicketService
{
    public function __construct(
        private TicketRepository $ticketRepository,
        private CreateTicketAction $createTicketAction,
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
        DB::beginTransaction();
        try {
            if (!empty($data['client_id'])) {
                $this->ensureClientProfileAction->execute($data['client_id']);
            }

            $ticket = $this->createTicketAction->execute($data);

            DB::commit();
            return ['id' => $ticket->id, 'ticketNumber' => $ticket->ticket_number];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
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