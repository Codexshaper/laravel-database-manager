<?php

namespace CodexShaper\DBM\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class CollectionField extends Model
{
    /*@var string*/
    protected $collection = 'dbm_collection_fields';
    /*@var array*/
    protected $casts = [
        'extra' => 'array',
    ];

    /**
     * Get collection for individual field.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->belongsTo(DBM_Collection::class, 'dbm_collection_id');
    }
}
