<?php

namespace App\Models\Treasury;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncomeAudit extends Model
{
    protected $table = 'treasury_income_audits';

    protected $fillable = [
        'income_id',
        'user_id',
        'edit_description',
        'subtotal_before',
        'discount_before',
        'total_before',
        'details_snapshot',
        'payment_methods_snapshot',
    ];

    protected $casts = [
        'subtotal_before'          => 'decimal:2',
        'discount_before'          => 'decimal:2',
        'total_before'             => 'decimal:2',
        'details_snapshot'         => 'array',
        'payment_methods_snapshot' => 'array',
    ];

    public function income(): BelongsTo
    {
        return $this->belongsTo(Income::class, 'income_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
