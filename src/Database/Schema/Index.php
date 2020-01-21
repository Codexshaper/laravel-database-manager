<?php

namespace CodexShaper\DBM\Database\Schema;

use Doctrine\DBAL\Schema\Index as DoctrineIndex;

class Index
{
    const PRIMARY = 'PRIMARY';
    const UNIQUE = 'UNIQUE';
    const INDEX = 'INDEX';

    /**
     * Create new index.
     *
     * @param array $index
     *
     * @return \Doctrine\DBAL\Schema\Index
     */
    public static function create($index)
    {
        $indexColumns = $index['columns'];
        if (! is_array($indexColumns)) {
            $indexColumns = [$indexColumns];
        }

        if (isset($index['type'])) {
            $type = $index['type'];

            $isPrimary = ($type == static::PRIMARY);
            $isUnique = $isPrimary || ($type == static::UNIQUE);
        } else {
            $isPrimary = $index['isPrimary'];
            $isUnique = $index['isUnique'];

            // Set the type
            if ($isPrimary) {
                $type = static::PRIMARY;
            } elseif ($isUnique) {
                $type = static::UNIQUE;
            } else {
                $type = static::INDEX;
            }
        }

        // Set the name
        $indexName = trim($index['name'] ?? '');
        if (empty($indexName)) {
            $table = $index['table'] ?? null;
            $indexName = static::createName($indexColumns, $type, $table);
        }

        $flags = $index['flags'] ?? [];
        $options = $index['options'] ?? [];

        return new DoctrineIndex($indexName, $indexColumns, $isUnique, $isPrimary, $flags, $options);
    }

    /**
     * Get index name.
     *
     * @param array $columns
     * @param string $type
     * @param string|null $table
     *
     * @return string
     */
    public static function createName($columns, $type, $table = null)
    {
        $table = isset($table) ? trim($table).'_' : '';
        $type = trim($type);
        $name = strtolower($table.implode('_', $columns).'_'.$type);

        return str_replace(['-', '.'], '_', $name);
    }

    /**
     * Get all indexes as an array.
     *
     * @return array
     */
    public static function toArray(DoctrineIndex $index)
    {
        return [
            'name' => $index->getName(),
            'oldName' => $index->getName(),
            'columns' => $index->getColumns(),
            'type' => static::getType($index),
            'isPrimary' => $index->isPrimary(),
            'isUnique' => $index->isUnique(),
            'isComposite' => (count($index->getColumns()) > 1) ? true : false,
            'flags' => $index->getFlags(),
            'options' => $index->getOptions(),
        ];
    }

    /**
     * Get index type.
     *
     * @return string
     */
    public static function getType(DoctrineIndex $index)
    {
        if ($index->isPrimary()) {
            return static::PRIMARY;
        } elseif ($index->isUnique() && ! $index->isPrimary()) {
            return static::UNIQUE;
        } elseif ($index->isSimpleIndex()) {
            return static::INDEX;
        }
    }
}
