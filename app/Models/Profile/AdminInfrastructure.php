<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\Model;
use App\Models\Core\Infrastructure;

class AdminInfrastructure extends Model
{
    protected $table = 'profile_admin_infrastructures';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'profile_admin_id',
        'core_infrastructure_id',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'profile_admin_id', 'core_person_id');
    }

    public function infrastructure()
    {
        return $this->hasMany(Infrastructure::class, 'id', 'core_infrastructure_id');
    }
}
