<?php

namespace App\Models\Inventory;

use App\Models\Auth\User;
use App\Models\Core\Infrastructure;
use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    use HasDataTable;

    protected $table = 'inventory_purchase_orders';

    protected $fillable = [
        'supplier_id',
        'infrastructure_id',
        'order_number',
        'status',
        'order_date',
        'expected_date',
        'received_date',
        'total_amount',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_date' => 'date',
        'received_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public static $searchColumns = [
        'order_number',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function infrastructure(): BelongsTo
    {
        return $this->belongsTo(Infrastructure::class, 'infrastructure_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class, 'purchase_order_id');
    }
}
