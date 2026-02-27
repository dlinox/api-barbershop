<?php

namespace App\Models\Treasury;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class IncomeDetail extends Model
{
    protected $table = 'treasury_income_details';

    protected $fillable = [
        'income_id',
        'itemable_type',
        'itemable_id',
        'description',
        'quantity',
        'unit_price',
        'discount',
        'subtotal',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'quantity' => 'integer',
    ];

    public function income(): BelongsTo
    {
        return $this->belongsTo(Income::class, 'income_id');
    }

    /**
     * Referencia polimórfica al ítem (producto, servicio, matrícula, etc.)
     */
    public function itemable(): MorphTo
    {
        return $this->morphTo();
    }
}
