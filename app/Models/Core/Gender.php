<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    protected $table = 'core_genders';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
    ];
}
