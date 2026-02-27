<?php

namespace App\Models\Treasury;

use App\Models\Core\PaymentMethods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncomePaymentMethod extends Model
{
    protected $table = 'treasury_income_payment_methods';

    protected $fillable = [
        'income_id',
        'payment_method_id',
        'amount',
        'payment_reference',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function income(): BelongsTo
    {
        return $this->belongsTo(Income::class, 'income_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethods::class, 'payment_method_id');
    }
}
