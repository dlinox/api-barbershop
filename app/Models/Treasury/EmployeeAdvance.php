<?php

namespace App\Models\Treasury;

use App\Common\Traits\HasDataTable;
use App\Models\Auth\User;
use App\Models\Core\Infrastructure;
use App\Models\Core\PaymentMethods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class EmployeeAdvance extends Model
{
    use HasDataTable;

    protected $table = 'treasury_employee_advances';

    protected static function boot()
    {
        parent::boot();

        static::creating(function (EmployeeAdvance $advance) {
            $advance->paid_by = $advance->paid_by ?? Auth::user()?->id;
            $advance->advance_date = $advance->advance_date ?? now();
        });
    }

    protected $fillable = [
        'employee_type',
        'employee_id',
        'infrastructure_id',
        'cash_session_id',
        'payment_method_id',
        'authorized_by',
        'paid_by',
        'amount',
        'advance_date',
        'payment_reference',
        'discounted_in_payment_id',
        'status',
        'reason',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'advance_date' => 'date',
    ];

    public static $searchColumns = [
        'payment_reference',
        'reason',
        'notes',
    ];

    // ─── Relaciones ───

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

    public function authorizedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'authorized_by');
    }

    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    /**
     * Empleado polimórfico (Worker, Barber, Teacher)
     */
    public function employee(): MorphTo
    {
        return $this->morphTo('employee', 'employee_type', 'employee_id');
    }

    /**
     * Pago donde se descontó este adelanto
     */
    public function discountedInPayment(): BelongsTo
    {
        return $this->belongsTo(EmployeePayment::class, 'discounted_in_payment_id');
    }

    // ─── Scopes ───

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDiscounted($query)
    {
        return $query->where('status', 'discounted');
    }
}
