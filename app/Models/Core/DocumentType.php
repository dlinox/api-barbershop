<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    protected $table = 'core_document_types';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
    ];
}
