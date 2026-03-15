<?php

namespace App\Models\Core;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasDataTable;

    protected $table = 'core_document_types';
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'code',
        'name',
    ];

    public static $searchColumns = [
        'code',
        'name',
    ];
}
