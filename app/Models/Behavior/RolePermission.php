<?php

namespace App\Models\Behavior;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'behavior_role_permissions';

    protected $fillable = [
        'behavior_role_id',
        'behavior_permission_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
