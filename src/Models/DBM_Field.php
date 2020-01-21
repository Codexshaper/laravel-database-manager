<?php

namespace CodexShaper\DBM\Models;

use Illuminate\Database\Eloquent\Model;

class DBM_Field extends Model
{
    /*@var string*/
    protected $table = 'dbm_fields';
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
        return $this->belongsTo(DBM_Object::class, 'dbm_object_id');
    }
}
