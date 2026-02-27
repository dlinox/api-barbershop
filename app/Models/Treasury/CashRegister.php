<?php

namespace App\Models\Treasury;

use App\Models\Core\Infrastructure;
use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashRegister extends Model
{
    use HasDataTable;

    protected $table = 'treasury_cash_registers';

    protected $fillable = [
        'infrastructure_id',
        'name',
        'is_active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static $searchColumns = [
        'name',
    ];

    public function infrastructure(): BelongsTo
    {
        return $this->belongsTo(Infrastructure::class, 'infrastructure_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(CashSession::class, 'cash_register_id');
    }
}
