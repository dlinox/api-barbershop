<?php

namespace App\Models\Treasury;

use App\Common\Traits\HasDataTable;
use App\Models\Core\File;
use App\Models\Core\Infrastructure;
use App\Models\Core\Person;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class Income extends Model
{
    use HasDataTable;

    protected $table = 'treasury_incomes';

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Income $income) {
            $income->user_id = $income->user_id ?? Auth::user()?->id;
            $income->transaction_date = $income->transaction_date ?? now();
            $income->receipt_number = $income->receipt_number
                ?? self::where('receipt_serie', $income->receipt_serie)->max('receipt_number') + 1;
        });
    }

    protected $fillable = [
        'cash_session_id',
        'infrastructure_id',
        'receipt_type',
        'receipt_serie',
        'receipt_number',
        'person_id',
        'transactionable_type',
        'transactionable_id',
        'subtotal',
        'discount',
        'tax',
        'total',
        'observations',
        'status',
        'is_edited',
        'transaction_date',
        'user_id',
    ];

    protected $casts = [
        'subtotal'         => 'decimal:2',
        'discount'         => 'decimal:2',
        'tax'              => 'decimal:2',
        'total'            => 'decimal:2',
        'is_edited'        => 'boolean',
        'transaction_date' => 'date',
    ];

    public static $searchColumns = [
        'receipt_serie',
        'receipt_number',
        'observations',
    ];

    // ─── Relaciones ───

    public function infrastructure(): BelongsTo
    {
        return $this->belongsTo(Infrastructure::class, 'infrastructure_id');
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function cashSession(): BelongsTo
    {
        return $this->belongsTo(CashSession::class, 'cash_session_id');
    }

    /**
     * Origen polimórfico (EnrollmentPayment, futuro Sale, etc.)
     */
    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function details(): HasMany
    {
        return $this->hasMany(IncomeDetail::class, 'income_id');
    }

    public function paymentMethods(): HasMany
    {
        return $this->hasMany(IncomePaymentMethod::class, 'income_id');
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function audits(): HasMany
    {
        return $this->hasMany(IncomeAudit::class, 'income_id')->orderByDesc('created_at');
    }
}
