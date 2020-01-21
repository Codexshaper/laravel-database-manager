<?php

namespace CodexShaper\DBM\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class DBM_Collection extends Model
{
    /*@var string*/
    protected $collection = 'dbm_collections';
    /*@var array*/
    protected $casts = [
        'extra' => 'array',
    ];

    /**
     * Get collection fields.
     *
     * @return \Illuminate\Support\Collection
     */
    public function fields()
    {
        return $this->hasMany(CollectionField::class, 'dbm_collection_id');
    }
}
