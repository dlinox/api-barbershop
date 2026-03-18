<?php

namespace App\Models\Treasury;

use App\Common\Traits\HasDataTable;
use App\Models\Auth\User;
use App\Models\Core\Infrastructure;
use App\Models\Core\PaymentMethod;
use App\Models\Core\PaymentMethods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class EmployeePayment extends Model
{
    use HasDataTable;

    protected $table = 'treasury_employee_payments';

    protected static function boot()
    {
        parent::boot();

        static::creating(function (EmployeePayment $payment) {
            $payment->paid_by = $payment->paid_by ?? Auth::user()?->id;
            $payment->payment_date = $payment->payment_date ?? now();
        });
    }

    protected $fillable = [
        'employee_type',
        'employee_id',
        'infrastructure_id',
        'cash_session_id',
        'payment_method_id',
        'paid_by',
        'period',
        'period_start',
        'period_end',
        'base_amount',
        'bonus',
        'deductions',
        'total_amount',
        'calculation_details',
        'payment_date',
        'payment_reference',
        'status',
        'notes',
    ];

    protected $casts = [
        'base_amount' => 'decimal:2',
        'bonus' => 'decimal:2',
        'deductions' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'calculation_details' => 'array',
        'period_start' => 'date',
        'period_end' => 'date',
        'payment_date' => 'date',
    ];

    public static $searchColumns = [
        'period',
        'payment_reference',
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
     * Adelantos descontados en este pago
     */
    public function discountedAdvances(): HasMany
    {
        return $this->hasMany(EmployeeAdvance::class, 'discounted_in_payment_id');
    }
}
