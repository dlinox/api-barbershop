<?php

namespace App\Models\Barbershop;

use App\Common\Traits\HasDataTable;
use App\Models\Auth\User;
use App\Models\Inventory\Sale;
use App\Models\Profile\Client;
use App\Models\Profile\Barber;
use App\Models\Treasury\CashSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class Ticket extends Model
{
    use HasDataTable;

    protected $table = 'barbershop_tickets';

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Ticket $ticket) {
            $ticket->auth_user_id = $ticket->auth_user_id ?? Auth::user()?->id;
        });
    }

    protected $fillable = [
        'branch_id',
        'cash_session_id',
        'reservation_id',
        'profile_barber_id',
        'profile_client_id',
        'auth_user_id',
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

    // public static $searchColumns = [
    //     'core_persons.name',
    // ];

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

    public function barber(): BelongsTo
    {
        return $this->belongsTo(Barber::class, 'profile_barber_id');
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auth_user_id');
    }
}
