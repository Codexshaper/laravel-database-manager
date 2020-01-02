<?php

namespace CodexShaper\DBM\Models;

use CodexShaper\DBM\Contracts\Relationships as RelationshipContract;
use CodexShaper\DBM\Models\DBM_Field;
use CodexShaper\DBM\Traits\Relationships;
use Illuminate\Database\Eloquent\Model;

class DBM_Object extends Model implements RelationshipContract
{
    use Relationships;

    protected $table = 'dbm_objects';

    protected $casts = [
        'details' => 'array',
    ];

    public function fields()
    {
        return $this->hasMany(DBM_Field::class, 'dbm_object_id');
    }

    public function allFields($order_by = 'order', $direction = 'ASC')
    {
        return $this->fields()->orderBy($order_by, $direction)->get();
    }

    public function createFields($order_by = 'order', $direction = 'ASC')
    {
        return $this->fields()->where('create', 1)->orderBy($order_by, $direction)->get();
    }

    public function readFields($order_by = 'order', $direction = 'ASC')
    {
        return $this->fields()->where('read', 1)->orderBy($order_by, $direction)->get();
    }

    public function editFields($order_by = 'order', $direction = 'ASC')
    {
        return $this->fields()->where('edit', 1)->orderBy($order_by, $direction)->get();
    }

    public function deleteFields($order_by = 'order', $direction = 'ASC')
    {
        return $this->fields()->where('delete', 1)->orderBy($order_by, $direction)->get();
    }

}
