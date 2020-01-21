<?php

namespace CodexShaper\DBM\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class DBM_MongoPermission extends Model
{
    /*@var string*/
    protected $collection = 'dbm_permissions';
    /*@var array*/
    protected $fillable = [
        'name', 'slug', 'prefix',
    ];

    /**
     * Get all users for permission.
     *
     * @return \Illuminate\Support\Collection
     */
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
