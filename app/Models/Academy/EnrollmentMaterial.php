<?php

namespace App\Models\Academy;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnrollmentMaterial extends Model
{
    protected $table = 'academy_enrollment_materials';

    protected $fillable = [
        'enrollment_id',
        'material_id',
        'quantity',
    ];

    protected $casts = [
        'enrollment_id' => 'integer',
        'material_id' => 'integer',
        'quantity' => 'integer',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }
}
