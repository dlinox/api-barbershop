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

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'brand_id',
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
        'is_for_sale' => 'boolean',
        'is_for_internal' => 'boolean',
        'is_active' => 'boolean',
    ];

    public static $searchColumns = [
        'inventory_products.name',
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
