<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{
    protected $table = 'core_files';

    protected $fillable = [
        'fileable_type',
        'fileable_id',
        'type',
        'name',
        'path',
        'disk',
        'mime_type',
        'size',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }
}
