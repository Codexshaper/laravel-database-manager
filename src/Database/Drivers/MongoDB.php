<?php

namespace CodexShaper\DBM\Database\Drivers;

use CodexShaper\DBM\Database\Drivers\MongoDB\Index;
use CodexShaper\DBM\Models\CollectionField;
use CodexShaper\DBM\Models\DBM_Collection;
use CodexShaper\DBM\Traits\MongoConnection;
use Illuminate\Support\Facades\DB;

class MongoDB
{
    use MongoConnection;

    /**
     * Run MongoDB command.
     *
     * @return  \MongoDB\Driver\Cursor
     */
    public function command(array $command)
    {
        return static::getMongoClient()->{$this->admin}->command($command);
    }

    /**
     * Rename collection.
     *
     * @param   string  $fromNs
     * @param   string  $toNs
     *
     * @return  \MongoDB\Driver\Cursor
     */
    public function renameCollection($fromNs, $toNs)
    {
        return $this->command(['renameCollection' => $fromNs, 'to' => $toNs]);
    }

    /**
     * Rename fields.
     *
     * @param   string  $collectionName
     * @param   array   $fields
     *
     * @return  void
     */
    public function renameFields($collectionName, $fields)
    {
        $rename = [];
        foreach ($fields as $oldName => $newName) {
            $rename[$oldName] = $newName;
        }
        $update = [
            '$rename' => $rename,
        ];

        $this->selectCollection($collectionName)->updateMany([], $update, ['upsert' => true]);
    }

    /**
     * Get the MongoDB database object.
     *
     * @return  \MongoDB\Database
     */
    public function getDB()
    {
        return DB::connection()->getMongoDB();
    }

    /**
     * Get the MongoDB collection namespace.
     *
     * @param   string  $databaseName
     * @param   string  $collectionName
     * @return string
     */
    public function getNamespace($databaseName, $collectionName)
    {
        return $databaseName.'.'.$collectionName;
    }

    /**
     * Get the all collections.
     *
     * @return  \MongoDB\Model\CollectionInfoIterator
     */
    public function getCollections()
    {
        return $this->getDB()->listCollections();
    }

    /**
     * Get the all collections name.
     *
     * @return  array
     */
    public function getCollectionNames()
    {
        $collections = $this->getCollections();
        $collectionNames = [];
        foreach ($collections as $key => $collection) {
            $collectionNames[] = $collection->getName();
        }

        return $collectionNames;
    }

    /**
     * Check MongoDB collection.
     *
     * @param   string $collectionName
     *
     * @return  bool
     */
    public function hasCollection($collectionName)
    {
        return (in_array($collectionName, $this->getCollectionNames())) ? true : false;
    }

    /**
     * Create MongoDB colelction.
     *
     * @param   string $collectionName
     *
     * @return  array|object Command result document
     */
    public function createCollection($collectionName)
    {
        return $this->getDB()->createCollection($collectionName);
    }

    /**
     * Get MongoDB colelction.
     *
     * @param   string $collectionName
     *
     * @return  array
     */
    public function getCollection($collectionName)
    {
        return [
            'name' => $collectionName,
            'oldName' => $collectionName,
            'columns' => $this->getColumns($collectionName),
            'indexes' => Index::getIndexes($this->selectCollection($collectionName)),
            'foreignKeys' => [],
            'primaryKeyName' => ['_id'],
            'options' => [],
        ];
    }

    /**
     * Update MongoDB colelction.
     *
     * @param   array $collection
     *
     * @return  bool
     */
    public function updateCollection($collection)
    {
        $newName = $collection['name'];
        $oldName = $collection['oldName'];
        $collectionName = $oldName;
        $connection = config('database.default');
        $database = config('database.connections.'.$connection.'.database');
        $fromNs = $this->getNamespace($database, $oldName);
        $toNs = $this->getNamespace($database, $newName);

        if ($newName != $oldName) {
            $this->renameCollection($fromNs, $toNs);
            $collectionName = $newName;
        }

        $this->setFields($collectionName, $this->getColumns($collectionName));
        Index::setIndexes($this->selectCollection($collectionName), $collection['indexes']);

        return true;
    }

    /**
     * Rename MongoDB colelction columns.
     *
     * @param   string $collectionName
     * @param   array $fields
     *
     * @return  void
     */
    public function renameColumns($collectionName, $fields)
    {
        $collection = $this->selectCollection($collectionName);
        $renames = [];
        foreach ($fields as $field) {
            if ($field->oldName != '') {
                if ($field->oldName != $field->name) {
                    $renames[$field->oldName] = $field->name;
                }
            }
        }
        $update = [];
        if ($field->oldName != '') {
            $update['$rename'] = $renames;
            $collection->updateMany([], $update, ['upsert' => true]);
            $dbmCollection = DBM_Collection::where('name', $collectionName)->first();
            foreach ($renames as $oldName => $newName) {
                $collection_field = CollectionField::where([
                    'dbm_collection_id' => $dbmCollection->_id,
                    'old_name' => $oldName,
                ])->first();
                $collection_field->old_name = $newName;
                $collection_field->update();
            }
        }
    }

    /**
     * Add MongoDB colelction columns.
     *
     * @param   string $collectionName
     *
     * @return  void
     */
    public function addColumns($collectionName)
    {
        $collection = $this->selectCollection($collectionName);
        $newFields = $this->getColumnsName($collectionName);
        $update = [];

        if ($collection->count() > 0) {
            foreach ($newFields as $newField) {
                $cursor = $collection->find();
                $iterator = iterator_to_array($cursor);

                foreach ($iterator as $document) {
                    $columnNames = [];
                    $id = '';
                    foreach ($document as $columnName => $columnValue) {
                        if (is_object($columnValue)) {
                            foreach ($columnValue as $key => $value) {
                                if ($columnName == '_id') {
                                    $id = $value;
                                }
                            }
                        }
                        $columnNames[] = $columnName;
                    }

                    if ($id != '' && ! in_array($newField, $columnNames)) {
                        $update['$set'] = [$newField => ''];
                        $collection->updateOne(
                            ['_id' => new \MongoDB\BSON\ObjectID($id)],
                            $update,
                            ['upsert' => true]
                        );
                    }
                }
            }
        }
    }

    /**
     * Remove MongoDB colelction columns.
     *
     * @param   string $collectionName
     *
     * @return  void
     */
    public function removeColumns($collectionName)
    {
        $collection = $this->selectCollection($collectionName);
        $newFields = $this->getColumnsName($collectionName);
        $columns = $this->getCollectionColumns($collectionName);
        $update = [];
        $unsets = [];

        foreach ($columns as $column) {
            if (! in_array($column, $newFields)) {
                $unsets[$column] = '';
            }
        }

        if (count($unsets) > 0) {
            $update['$unset'] = $unsets;
            $collection->updateMany([], $update, ['upsert' => true]);
        }
    }

    /**
     * Set MongoDB colelction fields.
     *
     * @param   string $collectionName
     * @param   array $fields
     *
     * @return  void
     */
    public function setFields($collectionName, $fields)
    {
        //Rename Columns
        $this->renameColumns($collectionName, $fields);
        //Add Columns
        $this->addColumns($collectionName);
        //Remove Columns
        $this->removeColumns($collectionName);
    }

    /**
     * Drop MongoDB colelction.
     *
     * @param   string $collectionName
     *
     * @return  void
     */
    public function dropCollection($collectionName)
    {
        $this->getDB()->dropCollection($collectionName);
    }

    /**
     * Select MongoDB colelction.
     *
     * @param   string $collectionName
     *
     * @return  \MongoDB\Collection
     */
    public function selectCollection($collectionName)
    {
        return $this->getDB()->selectCollection($collectionName);
    }

    /**
     * Get MongoDB colelction columns.
     *
     * @param   string $collectionName
     *
     * @return  array
     */
    public function getCollectionColumns($collectionName)
    {
        $cursor = $this->selectCollection($collectionName)->find();
        $iterator = iterator_to_array($cursor);
        $columnNames = [];

        foreach ($iterator as $document) {
            foreach ($document as $columnName => $columnValue) {
                $columnNames[] = $columnName;
            }
        }

        return array_values(array_unique($columnNames));
    }

    /**
     * Get MongoDB columns.
     *
     * @param   string $collectionName
     *
     * @return  array
     */
    public function getColumns($collectionName)
    {
        $columns = [];

        if ($collection = DBM_Collection::where('name', $collectionName)->first()) {
            $fields = $collection->fields;
            foreach ($fields as $field) {
                $columns[] = (object) [
                    'name' => $field->name,
                    'oldName' => $field->old_name,
                    'type' => [
                        'name' => $field->type,
                    ],
                    'autoincrement' => false,
                    'default' => null,
                    'length' => null,
                ];
            }
        } else {
            $fields = $this->getCollectionColumns($collectionName);
            foreach ($fields as $field) {
                $columns[] = (object) [
                    'name' => $field,
                    'oldName' => $field,
                    'type' => [
                        'name' => '',
                    ],
                    'autoincrement' => false,
                    'default' => null,
                    'length' => null,
                ];
            }
        }

        return $columns;
    }

    /**
     * Get colelction ColumnsName.
     *
     * @param   string $collectionName
     *
     * @return  array
     */
    public function getColumnsName($collectionName)
    {
        return DBM_Collection::where('name', $collectionName)->first()->fields->pluck('name')->toArray();
    }
}
