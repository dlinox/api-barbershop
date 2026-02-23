<?php

namespace App\Models\Inventory;


use App\Models\Auth\User;
use App\Models\Core\Infrastructure;
use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Auth;

class Kardex extends Model
{
    use HasDataTable;

    protected $table = 'inventory_kardex';

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Kardex $kardex) {
            $kardex->created_by = Auth::user()->id ?? null;
        });
    }

    protected $fillable = [
        'product_id',
        'presentation_id',
        'infrastructure_id',
        'movement_type',
        'reason',
        'quantity',
        'unit_cost',
        'total_cost',
        'balance_quantity',
        'balance_unit_cost',
        'balance_total_cost',
        'reference_id',
        'reference_type',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'balance_quantity' => 'integer',
        'balance_unit_cost' => 'decimal:2',
        'balance_total_cost' => 'decimal:2',
    ];

    public static $searchColumns = [
        'movement_type',
        'reason',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function presentation(): BelongsTo
    {
        return $this->belongsTo(ProductPresentation::class, 'presentation_id');
    }

    public function infrastructure(): BelongsTo
    {
        return $this->belongsTo(Infrastructure::class, 'infrastructure_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }
}
