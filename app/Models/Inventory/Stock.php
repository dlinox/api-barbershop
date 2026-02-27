<?php

namespace App\Models\Inventory;

use App\Common\Traits\HasDataTable;
use App\Models\Core\Infrastructure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    use HasDataTable;

    protected $table = 'inventory_stocks';

    protected $fillable = [
        'product_id',
        'presentation_id',
        'infrastructure_id',
        'current_stock',
        'last_movement_at',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'current_stock' => 'integer',
    ];

    public static $searchColumns = [
        'inventory_products.name',
        'inventory_product_presentations.sku',
        'inventory_product_presentations.name',
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
}
