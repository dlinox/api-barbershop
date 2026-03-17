<?php

namespace App\Models\Barbershop;

use App\Common\Traits\HasDataTable;
use App\Models\Profile\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasDataTable;

    protected $table = 'barbershop_reservations';

    protected $fillable = [
        'branch_id',
        'profile_client_id',
        'service_id',
        'date',
        'time',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public static $searchColumns = [
        'date',
        'status',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'profile_client_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
