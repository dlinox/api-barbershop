<?php

namespace App\Models\Academy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Common\Traits\HasDataTable;

class GroupPaymentPlan extends Model
{
    use HasDataTable;

    protected $table = 'academy_group_payment_plans';

    protected $fillable = [
        'group_id',
        'type',
        'start_date',
        'end_date',
        'amount',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }
}
