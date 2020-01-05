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

    protected $db;
    protected $collection;
    protected $databaseName;
    protected $collectionName;

    public function command(array $command)
    {
        return static::getMongoClient()->{$this->admin}->command($command);
    }

    public function renameCollection($fromNs, $toNs)
    {
        return $this->command(array('renameCollection' => $fromNs, 'to' => $toNs));
    }

    public function renameFields($collectionName, $fields)
    {
        $rename = [];
        foreach ($fields as $oldName => $newName) {
            $rename[$oldName] = $newName;
        }
        $update = array(
            '$rename' => $rename,
        );

        return $this->selectCollection($collectionName)->updateMany(array(), $update, array('upsert' => true));
    }

    public function getDB()
    {
        return DB::connection()->getMongoDB();
    }

    public function getNamespace($databaseName, $collectionName)
    {
        return $databaseName . '.' . $collectionName;
    }

    public function getCollections()
    {
        return $this->getDB()->listCollections();
    }

    public function getCollectionNames()
    {
        $collections     = $this->getCollections();
        $collectionNames = [];
        foreach ($collections as $key => $collection) {
            $collectionNames[] = $collection->getName();
        }

        return $collectionNames;
    }

    public function hasCollection($collectionName)
    {
        return (in_array($collectionName, $this->getCollectionNames())) ? true : false;
    }

    public function createCollection($collectionName)
    {
        return $this->getDB()->createCollection($collectionName);
    }

    public function getCollection($collectionName)
    {
        return [
            'name'           => $collectionName,
            'oldName'        => $collectionName,
            'columns'        => $this->getColumns($collectionName),
            'indexes'        => Index::getIndexes($this->selectCollection($collectionName)),
            'foreignKeys'    => [],
            'primaryKeyName' => ['_id'],
            'options'        => [],
        ];
    }

    public function updateCollection($collection)
    {

        $newName        = $collection['name'];
        $oldName        = $collection['oldName'];
        $collectionName = $oldName;
        $connection     = config('database.default');
        $database       = config('database.connections.' . $connection . ".database");
        $fromNs         = $this->getNamespace($database, $oldName);
        $toNs           = $this->getNamespace($database, $newName);

        if ($newName != $oldName) {
            $this->renameCollection($fromNs, $toNs);
            $collectionName = $newName;
        }

        $this->setFields($collectionName, $this->getColumns($collectionName));
        Index::setIndexes($this->selectCollection($collectionName), $collection['indexes']);

        return true;

    }

    public function setFields($collectionName, $fields)
    {
        $collection = $this->selectCollection($collectionName);
        /*
         * Rename Columns
         */
        $renames = [];
        foreach ($fields as $field) {
            if ($field->oldName != "") {
                if ($field->oldName != $field->name) {
                    $renames[$field->oldName] = $field->name;
                }

            }
        }
        $update = [];
        if (count($renames) > 0) {
            $update['$rename'] = $renames;
            $collection->updateMany(array(), $update, array('upsert' => true));
            foreach ($renames as $oldName => $newName) {
                $collection_field           = CollectionField::where('old_name', $oldName)->first();
                $collection_field->old_name = $newName;
                $collection_field->update();
            }
        }

        $newFields = $this->getColumnsName($collectionName);
        $columns   = $this->getCollectionColumns($collectionName);

        /*
         * Add Columns
         */

        if ($collection->count() > 0) {
            foreach ($newFields as $newField) {
                $cursor   = $collection->find();
                $iterator = iterator_to_array($cursor);

                foreach ($iterator as $document) {
                    $columnNames = [];
                    $id          = "";
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

                    if ($id != "" && !in_array($newField, $columnNames)) {
                        $update['$set'] = array($newField => "");
                        $collection->updateOne(
                            array("_id" => new \MongoDB\BSON\ObjectID($id)),
                            $update,
                            array('upsert' => true)
                        );
                    }
                }
            }

        }

        /*
         * Remove Columns
         */

        $update = [];

        $unsets = [];
        foreach ($columns as $column) {
            if (!in_array($column, $newFields)) {
                $unsets[$column] = "";
            }
        }

        if (count($unsets) > 0) {
            $update['$unset'] = $unsets;
            $collection->updateMany(array(), $update, array('upsert' => true));
        }

        return true;
    }

    public function dropCollection($collectionName)
    {
        $this->getDB()->dropCollection($collectionName);
    }

    public function selectCollection($collectionName)
    {
        return $this->getDB()->selectCollection($collectionName);
    }

    public function getCollectionColumns($collectionName)
    {
        $cursor      = $this->selectCollection($collectionName)->find();
        $iterator    = iterator_to_array($cursor);
        $columnNames = [];

        foreach ($iterator as $document) {
            foreach ($document as $columnName => $columnValue) {
                $columnNames[] = $columnName;
            }
        }

        return array_values(array_unique($columnNames));
    }

    public function getColumns($collectionName)
    {
        $columns = [];

        if ($collection = DBM_Collection::where('name', $collectionName)->first()) {

            $fields = $collection->fields;
            foreach ($fields as $field) {
                $columns[] = (object) [
                    "name"          => $field->name,
                    "oldName"       => $field->old_name,
                    "type"          => [
                        "name" => $field->type,
                    ],
                    "autoincrement" => false,
                    "default"       => null,
                    "length"        => null,
                ];
            }
        } else {
            $fields = $this->getCollectionColumns($collectionName);
            foreach ($fields as $field) {
                $columns[] = (object) [
                    "name"          => $field,
                    "oldName"       => $field,
                    "type"          => [
                        "name" => "",
                    ],
                    "autoincrement" => false,
                    "default"       => null,
                    "length"        => null,
                ];
            }
        }

        return $columns;
    }

    public function getColumnsName($collectionName)
    {
        return DBM_Collection::where('name', $collectionName)->first()->fields->pluck('name')->toArray();
    }

}
