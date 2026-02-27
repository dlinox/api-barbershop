<?php

namespace App\Models\Academy;

use App\Common\Traits\HasDataTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Material extends Model
{
    use HasDataTable;

    protected $table = 'academy_materials';

    protected $fillable = [
        'presentation_id',
        'quantity',
        'is_active',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'presentation_id' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static $searchColumns = [
        'academy_materials.presentation_id',
    ];

    public function enrollments(): BelongsToMany
    {
        return $this->belongsToMany(Enrollment::class, 'academy_enrollment_materials', 'material_id', 'enrollment_id');
    }

    public function presentation()
    {
        return $this->belongsTo(\App\Models\Inventory\ProductPresentation::class, 'presentation_id');
    }
}
