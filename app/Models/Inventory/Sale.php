<?php

namespace App\Models\Inventory;

use App\Common\Traits\HasDataTable;
use App\Models\Auth\User;
use App\Models\Barbershop\Ticket;
use App\Models\Core\Infrastructure;
use App\Models\Core\Person;
use App\Models\Treasury\CashSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Sale extends Model
{
    use HasDataTable;

    protected $table = 'inventory_sales';

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Sale $sale) {
            $sale->user_id = $sale->user_id ?? Auth::user()?->id;
        });
    }

    protected $fillable = [
        'infrastructure_id',
        'cash_session_id',
        'barbershop_ticket_id',
        'person_id',
        'context',
        'subtotal',
        'discount',
        'total',
        'status',
        'user_id',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total'    => 'decimal:2',
    ];

    public function infrastructure(): BelongsTo
    {
        return $this->belongsTo(Infrastructure::class, 'infrastructure_id');
    }

    public function cashSession(): BelongsTo
    {
        return $this->belongsTo(CashSession::class, 'cash_session_id');
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'barbershop_ticket_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class, 'sale_id');
    }
}
