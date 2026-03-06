<?php

namespace App\Models\Barbershop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketService extends Model
{
    protected $table = 'barbershop_ticket_services';

    protected $fillable = [
        'ticket_id',
        'service_branch_id',
        'quantity',
        'amount',
        'discount',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'amount'   => 'decimal:2',
        'discount' => 'decimal:2',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function serviceBranch(): BelongsTo
    {
        return $this->belongsTo(ServiceBranch::class, 'service_branch_id');
    }
}
