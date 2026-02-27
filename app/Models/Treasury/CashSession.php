<?php

namespace App\Models\Treasury;

use App\Models\Auth\User;
use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashSession extends Model
{
    use HasDataTable;

    protected $table = 'treasury_cash_sessions';

    protected $fillable = [
        'cash_register_id',
        'opened_by',
        'closed_by',
        'opening_amount',
        'expected_closing_amount',
        'actual_closing_amount',
        'difference',
        'status',
        'opened_at',
        'closed_at',
        'notes',
    ];

    protected $casts = [
        'opening_amount' => 'decimal:2',
        'expected_closing_amount' => 'decimal:2',
        'actual_closing_amount' => 'decimal:2',
        'difference' => 'decimal:2',
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public static $searchColumns = [
        'status',
    ];

    public function cashRegister(): BelongsTo
    {
        return $this->belongsTo(CashRegister::class, 'cash_register_id');
    }

    public function openedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    public function closedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'cash_session_id');
    }
}
