<?php

namespace CodexShaper\DBM\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class DBM_MongoField extends Model
{
    /*@var string*/
    protected $collection = 'dbm_fields';
    /*@var array*/
    protected $casts = [
        'settings' => 'array',
    ];

    /**
     * Get object for individual field.
     *
     * @return \Illuminate\Support\Collection
     */
    public function object()
    {
        return $this->belongsTo(DBM_MongoObject::class, 'dbm_object_id');
    }
}
