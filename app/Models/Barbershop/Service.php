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
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public static $searchColumns = [
        'barbershop_services.name',
        'barbershop_services.description',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function serviceBranches(): HasMany
    {
        return $this->hasMany(ServiceBranch::class, 'service_id');
    }
}
