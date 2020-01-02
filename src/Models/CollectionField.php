<?php

namespace CodexShaper\DBM\Models;

use CodexShaper\DBM\Models\DBM_Collection;
use Jenssegers\Mongodb\Eloquent\Model;

class CollectionField extends Model
{
    protected $collection = 'dbm_collection_fields';

    protected $casts = [
        'extra' => 'array',
    ];

    public function collection()
    {
        return $this->belongsTo(DBM_Collection::class, 'dbm_collection_id');
    }
}
