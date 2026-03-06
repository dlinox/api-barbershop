<?php

namespace App\Models\Barbershop;

use App\Common\Traits\HasDataTable;
use App\Models\Inventory\Sale;
use App\Models\Profile\Client;
use App\Models\Profile\Worker;
use App\Models\Treasury\CashSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    use HasDataTable;

    protected $table = 'barbershop_tickets';

    protected $fillable = [
        'branch_id',
        'cash_session_id',
        'reservation_id',
        'profile_worker_id',
        'profile_client_id',
        'amount',
        'discount',
        'total',
        'ticket_date',
        'status',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'discount'    => 'decimal:2',
        'total'       => 'decimal:2',
        'ticket_date' => 'datetime',
    ];

    public static $searchColumns = [
        'barbershop_tickets.status',
        'barbershop_branches.name',
        'worker_persons.name',
        'client_persons.name',
        'client_persons.paternal_surname',
        'client_persons.document_number',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function cashSession(): BelongsTo
    {
        return $this->belongsTo(CashSession::class, 'cash_session_id');
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'reservation_id');
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'profile_worker_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'profile_client_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(TicketService::class, 'ticket_id');
    }

    public function sale(): HasOne
    {
        return $this->hasOne(Sale::class, 'barbershop_ticket_id');
    }
}
