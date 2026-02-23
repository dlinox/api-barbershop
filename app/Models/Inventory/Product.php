<?php

namespace App\Models\Inventory;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasDataTable;

    protected $table = 'inventory_products';

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Product $product) {
            $product->sku = self::generateSku();
        });
    }

    public static function generateSku(): string
    {
        $last = self::orderBy('id', 'desc')->first();
        $nextNumber = $last ? $last->id + 1 : 1;

        return 'PROD-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    protected $fillable = [
        'sku',
        'name',
        'description',
        'category_id',
        'brand_id',
        'min_stock',
        'max_stock',
        'is_for_sale',
        'is_for_internal',
        'image_url',
        'is_active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'min_stock' => 'integer',
        'max_stock' => 'integer',
        'is_for_sale' => 'boolean',
        'is_for_internal' => 'boolean',
        'is_active' => 'boolean',
    ];

    public static $searchColumns = [
        'name',
        'sku',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function presentations(): HasMany
    {
        return $this->hasMany(ProductPresentation::class, 'product_id');
    }

    public function kardex(): HasMany
    {
        return $this->hasMany(Kardex::class, 'product_id');
    }
}
