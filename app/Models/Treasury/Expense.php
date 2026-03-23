<?php

namespace App\Models\Treasury;

use App\Common\Traits\HasDataTable;
use App\Models\Auth\User;
use App\Models\Core\Infrastructure;
use App\Models\Core\PaymentMethods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Expense extends Model
{
    use HasDataTable;

    protected $table = 'treasury_expenses';

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Expense $expense) {
            $expense->user_id = $expense->user_id ?? Auth::user()?->id;
            $expense->transaction_date = $expense->transaction_date ?? now()->toDateString();
        });
    }

    protected $fillable = [
        'expense_type_id',
        'infrastructure_id',
        'cash_session_id',
        'payment_method_id',
        'user_id',
        'amount',
        'description',
        'transaction_date',
        'voucher_date',
        'voucher_number',
        'voucher_image_path',
        'status',
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'transaction_date' => 'date',
        'voucher_date'     => 'date',
    ];

    public static $searchColumns = [
        'description',
        'voucher_number',
    ];

    // ─── Relationships ───

    public function expenseType(): BelongsTo
    {
        return $this->belongsTo(ExpenseType::class, 'expense_type_id');
    }

    public function infrastructure(): BelongsTo
    {
        return $this->belongsTo(Infrastructure::class, 'infrastructure_id');
    }

    public function cashSession(): BelongsTo
    {
        return $this->belongsTo(CashSession::class, 'cash_session_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethods::class, 'payment_method_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ─── Scopes ───

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}
