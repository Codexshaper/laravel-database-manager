<?php

namespace CodexShaper\DBM\Models;

use Illuminate\Database\Eloquent\Model;

class DBM_Permission extends Model
{
    protected $table = 'dbm_permissions';

    protected $fillable = [
        'name', 'slug', 'prefix',
    ];

    public function users()
    {
        return $this->belongsToMany(
            config('dbm.user.model'),
            'dbm_user_permissions',
            'dbm_permission_id',
            'user_id'
        );
    }
}
