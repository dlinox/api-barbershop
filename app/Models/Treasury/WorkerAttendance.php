<?php

namespace App\Models\Treasury;

use App\Common\Traits\HasDataTable;
use App\Models\Profile\Worker;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkerAttendance extends Model
{
    use HasDataTable;

    protected $table = 'worker_attendances';

    protected $fillable = [
        'worker_id',
        'date',
        'check_in',
        'check_out',
        'check_token',
        'check_type',
        'status',
        'observation',
    ];

    protected $casts = [
        'worker_id' => 'integer',
        'date' => 'date',
    ];

    public static $searchColumns = [
        'core_persons.name',
        'core_persons.paternal_surname',
        'core_persons.maternal_surname',
        'core_persons.document_number',
    ];

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'worker_id', 'id');
    }
}
