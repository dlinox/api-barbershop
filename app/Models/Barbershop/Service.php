<?php

namespace App\Models\Barbershop;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasDataTable;

    protected $table = 'barbershop_services';

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'branch_id',
        'price',
        'duration',
        'is_active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'integer',
        'duration' => 'integer',
    ];

    public static $searchColumns = [
        'barbershop_services.name',
        'barbershop_services.description',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
