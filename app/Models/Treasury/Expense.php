<?php

namespace App\Models\Treasury;

use App\Models\Auth\User;
use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Expense extends Model
{
    protected $table = 'treasury_expenses';

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Expense $expense) {
            $expense->user_id = $expense->user_id ?? Auth::user()?->id;
        });
    }

    protected $fillable = [
        'cash_session_id',
        'user_id',
        'amount',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function cashSession(): BelongsTo
    {
        return $this->belongsTo(CashSession::class, 'cash_session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
