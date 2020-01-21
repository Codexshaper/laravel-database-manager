<?php

namespace CodexShaper\DBM\Models;

use CodexShaper\DBM\Traits\Relationships;
use Jenssegers\Mongodb\Eloquent\Model;

class DBM_MongoObject extends Model
{
    use Relationships;

    /*@var string*/
    protected $collection = 'dbm_objects';
    /*@var array*/
    protected $casts = [
        'details' => 'array',
    ];

    /**
     * Get object fields.
     *
     * @return \Illuminate\Support\Collection
     */
    public function fields()
    {
        return $this->hasMany(DBM_MongoField::class, 'dbm_object_id');
    }

    /**
     * Get all fields.
     *
     * @param string $order_by
     * @param string $direction
     *
     * @return \Illuminate\Support\Collection
     */
    public function allFields($order_by = 'order', $direction = 'ASC')
    {
        return $this->fields()->orderBy($order_by, $direction)->get();
    }

    /**
     * Get Create fields.
     *
     * @param string $order_by
     * @param string $direction
     *
     * @return \Illuminate\Support\Collection
     */
    public function createFields($order_by = 'order', $direction = 'ASC')
    {
        return $this->fields()->where('create', true)->orderBy($order_by, $direction)->get();
    }

    /**
     * Get Browse fields.
     *
     * @param string $order_by
     * @param string $direction
     *
     * @return \Illuminate\Support\Collection
     */
    public function readFields($order_by = 'order', $direction = 'ASC')
    {
        return $this->fields()->where('read', true)->orderBy($order_by, $direction)->get();
    }

    /**
     * Get Edit fields.
     *
     * @param string $order_by
     * @param string $direction
     *
     * @return \Illuminate\Support\Collection
     */
    public function editFields($order_by = 'order', $direction = 'ASC')
    {
        return $this->fields()->where('edit', true)->orderBy($order_by, $direction)->get();
    }

    /**
     * Get delete fields.
     *
     * @param string $order_by
     * @param string $direction
     *
     * @return \Illuminate\Support\Collection
     */
    public function deleteFields($order_by = 'order', $direction = 'ASC')
    {
        return $this->fields()->where('delete', true)->orderBy($order_by, $direction)->get();
    }
}
