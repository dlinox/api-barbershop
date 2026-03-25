<?php

namespace App\Models\Reports;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Auth\User;

class Report extends Model
{
    use HasFactory;


    protected static function boot()
    {
        parent::boot();

        static::creating(function (Report $report) {
            $report->generated_by = Auth::user()->id ?? null;
        });
    }

    protected $fillable = [
        'name',
        'reference',
        'version',
        'type',
        'data',
        'file_path',
        'generated_by',
    ];

    protected $casts = [
        'data' => 'array',
        'version' => 'integer',
    ];

    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
