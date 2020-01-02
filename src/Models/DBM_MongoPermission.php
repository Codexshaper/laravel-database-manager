<?php

namespace CodexShaper\DBM\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class DBM_MongoPermission extends Model
{
    protected $collection = 'dbm_permissions';

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
