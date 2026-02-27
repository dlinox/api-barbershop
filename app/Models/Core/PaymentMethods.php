<?php

namespace App\Models\Core;

use App\Common\Traits\HasDataTable;
use App\Common\Traits\HasInfrastructure;
use Illuminate\Database\Eloquent\Model;

class PaymentMethods extends Model
{
    use HasDataTable, HasInfrastructure;

    protected $table = 'core_payment_methods';

    protected $fillable = [
        'name',
        'type',
        'is_active',
        'is_default',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    public static $searchColumns = [
        'name',
        'type',
    ];
}
