<?php

namespace CodexShaper\DBM\Database\Drivers\MongoDB;

use CodexShaper\DBM\Models\CollectionField;
use CodexShaper\DBM\Models\DBM_Collection;

class Collection
{
    /*@var string*/
    protected $collection;
    /*@var array*/
    protected $columns = [];
    /*@var string*/
    protected $name;

    /**
     * MongoDB collection constuctor.
     *
     * @return void
     */
    public function __construct($collection)
    {
        $this->collection = $collection;
    }

    /**
     * Add MongoDB collection column.
     *
     * @param string $name
     * @param string $type
     *
     * @return $this
     */
    public function addColumn($name, $type)
    {
        $this->name = $name;

        $this->columns[$name] = [
            'name' => $name,
            'old_name' => $name,
            'type' => $type,
            'index' => '',
            'default' => null,
            'extra' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        return $this;
    }

    /**
     * Add index.
     *
     * @return $this
     */
    public function index()
    {
        $this->columns[$this->name]['index'] = 'INDEX';

        return $this;
    }

    /**
     * Add unique key.
     *
     * @return $this
     */
    public function unique()
    {
        $this->columns[$this->name]['index'] = 'UNIQUE';

        return $this;
    }

    /**
     * Add default value.
     *
     * @param string|bool|int|null $value
     *
     * @return $this
     */
    public function defaultValue($value)
    {
        $this->columns[$this->name]['default'] = $value;

        return $this;
    }

    /**
     * Add nullable column.
     *
     * @return $this
     */
    public function nullable()
    {
        $this->default(null);

        return $this;
    }

    /**
     * Save collection and fields.
     *
     * @return void
     */
    public function save()
    {
        if ($collection = DBM_Collection::where('name', $this->collection)->first()) {
            $collection->fields()->delete();
            $collection->delete();
        }

        $collection = new DBM_Collection;
        $collection->name = $this->collection;
        $collection->old_name = $this->collection;
        $collection->extra = '';
        $collection->created_at = now();
        $collection->updated_at = now();
        $collection->save();

        foreach ($this->columns as $column) {
            $field = new CollectionField;
            $field->dbm_collection_id = $collection->_id;
            foreach ($column as $key => $value) {
                $field->{$key} = $value;
            }

            $field->save();
        }

        $this->columns = [];
        $this->name = '';
    }
}
