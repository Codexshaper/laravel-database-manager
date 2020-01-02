<?php

namespace CodexShaper\DBM\Models;

use CodexShaper\DBM\Models\DBM_Object;
use Illuminate\Database\Eloquent\Model;

class DBM_Field extends Model
{
    protected $table = 'dbm_fields';

    protected $casts = [
        'settings' => 'array',
    ];

    public function object()
    {
        return $this->belongsTo(DBM_Object::class, 'dbm_object_id');
    }
}
