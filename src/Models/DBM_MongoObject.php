<?php

namespace CodexShaper\DBM\Models;

use CodexShaper\DBM\Models\DBM_MongoField;
use CodexShaper\DBM\Traits\Relationships;
use Jenssegers\Mongodb\Eloquent\Model;

class DBM_MongoObject extends Model
{
    use Relationships;

    protected $collection = 'dbm_objects';

    protected $casts = [
        'details' => 'array',
    ];

    public function fields()
    {
        return $this->hasMany(DBM_MongoField::class, 'dbm_object_id');
    }

    public function allFields($order_by = 'order', $direction = 'ASC')
    {
        return $this->fields()->orderBy($order_by, $direction)->get();
    }

    public function createFields($order_by = 'order', $direction = 'ASC')
    {
        return $this->fields()->where('create', true)->orderBy($order_by, $direction)->get();
    }

    public function readFields($order_by = 'order', $direction = 'ASC')
    {
        return $this->fields()->where('read', true)->orderBy($order_by, $direction)->get();
    }

    public function editFields($order_by = 'order', $direction = 'ASC')
    {
        return $this->fields()->where('edit', true)->orderBy($order_by, $direction)->get();
    }

    public function deleteFields($order_by = 'order', $direction = 'ASC')
    {
        return $this->fields()->where('delete', true)->orderBy($order_by, $direction)->get();
    }

}
