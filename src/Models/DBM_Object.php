<?php

namespace CodexShaper\DBM\Models;

use CodexShaper\DBM\Contracts\Relationships as RelationshipContract;
use CodexShaper\DBM\Traits\Relationships;
use Illuminate\Database\Eloquent\Model;

class DBM_Object extends Model implements RelationshipContract
{
    use Relationships;

    /*@var string*/
    protected $table = 'dbm_objects';
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
        return $this->hasMany(DBM_Field::class, 'dbm_object_id');
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
        return $this->fields()->where('create', 1)->orderBy($order_by, $direction)->get();
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
        return $this->fields()->where('read', 1)->orderBy($order_by, $direction)->get();
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
        return $this->fields()->where('edit', 1)->orderBy($order_by, $direction)->get();
    }

    /**
     * Get Delete fields.
     *
     * @param string $order_by
     * @param string $direction
     *
     * @return \Illuminate\Support\Collection
     */
    public function deleteFields($order_by = 'order', $direction = 'ASC')
    {
        return $this->fields()->where('delete', 1)->orderBy($order_by, $direction)->get();
    }
}
