<?php

namespace App\Models\Inventory;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPresentation extends Model
{
    use HasDataTable;

    protected $table = 'inventory_product_presentations';

    protected static function boot()
    {
        parent::boot();

        static::creating(function (ProductPresentation $presentation) {
            if (empty($presentation->sku)) {
                $presentation->sku = self::generateSku();
            }
        });
    }

    public static function generateSku(): string
    {
        $last = self::orderBy('id', 'desc')->first();
        $nextNumber = $last ? $last->id + 1 : 1;

        return 'SKU-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    protected $fillable = [
        'product_id',
        'sku',
        'name',
        'unit_type',
        'quantity',
        'barcode',
        'min_stock',
        'max_stock',
        'cost_price',
        'sale_price',
        'is_default',
        'is_active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'min_stock' => 'integer',
        'max_stock' => 'integer',
        'cost_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    //stock
    public function stock()
    {
        return $this->hasOne(Stock::class, 'presentation_id');
    }
}
