<?php

namespace CodexShaper\DBM\Database\Drivers\MongoDB;

use CodexShaper\DBM\Database\Drivers\MongoDB\Traits\IndexTrait;
use MongoDB\Collection;
use MongoDB\Model\IndexInfo;

class Index
{
    use IndexTrait;

    /**
     * Get Indexes.
     *
     * @return  array
     */
    public static function getIndexes(Collection $collection)
    {
        $listIndexes = $collection->listIndexes();
        $indexes = [];
        foreach ($listIndexes as $index) {
            $indexes[] = [
                'name' => $index->getName(),
                'oldName' => $index->getName(),
                'columns' => array_keys($index->getKey()),
                'type' => static::getType($index),
                'isPrimary' => false,
                'isUnique' => $index->isUnique(),
                'isComposite' => (count($index->getKey()) > 1) ? true : false,
                'flags' => [],
                'options' => [],
                'namespace' => $index->getNamespace(),
            ];
        }

        return $indexes;
    }

    /**
     * Get index type.
     *
     * @return  string
     */
    public static function getType(IndexInfo $index)
    {
        $type = static::getCommonType($index);

        if (empty($type)) {
            $type = static::getSpecialType($index);
        }

        if (empty($type)) {
            $type = static::getDefaultType($index);
        }

        return $type;
    }

    /**
     * Set Indexes.
     *
     * @param   \MongoDB\Collection     $collection
     * @param   array   $indexes
     *
     * @return  bool
     */
    public static function setIndexes(Collection $collection, $indexes)
    {
        $collection->dropIndexes();

        foreach ($indexes as $index) {
            $columns = $index['columns'];
            $name = $index['name'];
            $type = $index['type'];

            foreach ($columns as $column) {
                if ($column == '_id') {
                    continue;
                }

                $indexDetails = static::getIndexDetails($type);
                $indexType = $indexDetails['type'];
                $options = $indexDetails['options'];

                $options['name'] = strtolower($collection->getCollectionName().'_'.$column.'_'.$type);

                $options['ns'] = $collection->getNamespace();

                $collection->createIndex([$column => $indexType], $options);
            }
        }

        return true;
    }

    /**
     * Get Index Details.
     *
     * @param   string   $type
     *
     * @return  array
     */
    public static function getIndexDetails($type)
    {
        $indexType = 1;
        $options = [];

        switch ($type) {

            case 'TEXT':
                $indexType = 'text';
                break;
            case 'INDEX':
                $indexType = 1;
                break;
            case 'UNIQUE':
                $indexType = 1;
                $options['unique'] = true;
                break;
            case 'UNIQUE_DESC':
                $indexType = -1;
                $options['unique'] = true;
                break;
            case 'TTL':
                $indexType = 1;
                $options['expireAfterSeconds'] = 3600;
                break;
            case 'SPARSE':
                $indexType = 1;
                $options['sparse'] = true;
                break;
            case 'SPARSE_DESC':
                $indexType = -1;
                $options['sparse'] = true;
                break;
            case 'SPARSE_UNIQUE':
                $indexType = 1;
                $options['sparse'] = true;
                $options['unique'] = true;
            case 'SPARSE_UNIQUE_DESC':
                $indexType = -1;
                $options['sparse'] = true;
                $options['unique'] = true;
                break;
            case 'ASC':
                $indexType = 1;
                break;
            case 'DESC':
                $indexType = -1;
                break;
        }

        return ['type' => $indexType, 'options' => $options];
    }
}
