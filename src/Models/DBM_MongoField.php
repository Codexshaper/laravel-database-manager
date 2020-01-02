<?php

namespace CodexShaper\DBM\Models;

use CodexShaper\DBM\Models\DBM_MongoObject;
use Jenssegers\Mongodb\Eloquent\Model;

class DBM_MongoField extends Model
{
    protected $collection = 'dbm_fields';

    protected $casts = [
        'settings' => 'array',
    ];

    public function object()
    {
        return $this->belongsTo(DBM_MongoObject::class, 'dbm_object_id');
    }
}
