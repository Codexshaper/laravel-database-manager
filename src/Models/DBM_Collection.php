<?php

namespace CodexShaper\DBM\Models;

use CodexShaper\DBM\Models\CollectionField;
use Jenssegers\Mongodb\Eloquent\Model;

class DBM_Collection extends Model
{
    protected $collection = 'dbm_collections';

    protected $casts = [
        'extra' => 'array',
    ];

    public function fields()
    {
        return $this->hasMany(CollectionField::class, 'dbm_collection_id');
    }
}
