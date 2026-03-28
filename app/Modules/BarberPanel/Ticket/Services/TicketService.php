<?php

namespace App\Modules\BarberPanel\Ticket\Services;

use App\Models\Barbershop\Ticket;
use App\Models\Profile\Barber;
use App\Modules\Administrator\Barbershop\Repositories\Actions\CancelTicketAction;
use App\Modules\Administrator\Barbershop\Repositories\Actions\ConfirmTicketAction;
use App\Modules\Administrator\Barbershop\Repositories\Actions\CreateTicketAction;
use App\Modules\BarberPanel\Shared\BarberContext;
use App\Modules\BarberPanel\Ticket\Repositories\TicketRepository;
use Illuminate\Support\Facades\DB;

class TicketService
{
    public function __construct(
        private readonly TicketRepository $repository,
        private readonly CreateTicketAction $createTicketAction,
        private readonly ConfirmTicketAction $confirmTicketAction,
        private readonly CancelTicketAction $cancelTicketAction,
    ) {}

    public function dataTable($request)
    {
        return $this->repository->dataTable($request, BarberContext::barberId());
    }

    public function ticketsOverview(int $cashSessionId): array
    {
        return $this->repository->ticketsOverview($cashSessionId, BarberContext::barberId());
    }

    public function findById(int $id): Ticket
    {
        return $this->repository->findById($id, BarberContext::barberId());
    }

    public function waitingQueue(int $cashSessionId)
    {
        return $this->repository->waitingQueue($cashSessionId, BarberContext::barberId());
    }

    public function save(array $data): array
    {
        $barberId = BarberContext::barberId();
        $barber = Barber::findOrFail($barberId);
        $branchId = $barber->branch_id;
        $infrastructureId = $barber->branch->getInfrastructureId();

        $data['barber_id'] = $barberId;
        $data['branch_id'] = $branchId;

        return DB::transaction(function () use ($data, $infrastructureId) {
            $ticket = $this->createTicketAction->execute($data);

            $result = ['ticket' => $ticket, 'incomeId' => null];

            if (!empty($data['income'])) {
                [$ticket, $income] = $this->confirmTicketAction->execute(
                    $ticket,
                    $data,
                    $infrastructureId,
                );
                $result['incomeId'] = $income->id;
            }

            return $result;
        });
    }

    public function cancel(int $id): Ticket
    {
        $barberId = BarberContext::barberId();

        $ticket = Ticket::where('profile_barber_id', $barberId)->findOrFail($id);

        return $this->cancelTicketAction->execute($ticket->id);
    }

    public function getServicesByInfrastructure(int $infrastructureId)
    {
        return $this->repository->getServicesByInfrastructure($infrastructureId);
    }
}
