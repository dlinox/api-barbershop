<?php

namespace App\Models\Barbershop;

use App\Common\Traits\HasDataTable;
use App\Models\Profile\Barber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarberAttendance extends Model
{
    use HasDataTable;

    protected $table = 'barber_attendances';

    protected $fillable = [
        'branch_id',
        'barber_id',
        'date',
        'check_in',
        'check_out',
        'check_token',
        'check_type',
        'status',
        'observation',
    ];

    protected $casts = [
        'branch_id' => 'integer',
        'barber_id' => 'integer',
        'date' => 'date',
    ];

    public static $searchColumns = [
        'core_persons.name',
        'core_persons.paternal_surname',
        'core_persons.maternal_surname',
        'core_persons.document_number',
    ];

    public function barber(): BelongsTo
    {
        return $this->belongsTo(Barber::class, 'barber_id', 'id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }
}
