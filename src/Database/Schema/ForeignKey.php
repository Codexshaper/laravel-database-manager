<?php

namespace CodexShaper\DBM\Database\Schema;

use Doctrine\DBAL\Schema\ForeignKeyConstraint as DoctrineForeignKey;
use Doctrine\DBAL\Schema\SchemaException;

class ForeignKey
{
    /**
     * Create foreign key.
     *
     * @param array $foreignKey
     *
     * @return \Doctrine\DBAL\Schema\ForeignKeyConstraint
     */
    public static function create($foreignKey)
    {
        // Set the local table
        $localTable = null;
        if (isset($foreignKey['localTable'])) {
            $localTable = static::getDoctrineTable($foreignKey['localTable']);
        }

        $localColumns = $foreignKey['localColumns'];
        $foreignTable = $foreignKey['foreignTable'];
        $foreignColumns = $foreignKey['foreignColumns'];
        $options = $foreignKey['options'] ?? [];

        // Set the name
        $name = isset($foreignKey['name']) ? trim($foreignKey['name']) : '';
        if (empty($name)) {
            $table = isset($localTable) ? $localTable->getName() : null;
            $name = static::createName($localColumns, 'foreign', $table);
        } else {
            // $name = Identifier::validate($name, 'Foreign Key');
        }

        $doctrineForeignKey = new DoctrineForeignKey(
            $localColumns, $foreignTable, $foreignColumns, $name, $options
        );

        if (isset($localTable)) {
            $doctrineForeignKey->setLocalTable($localTable);
        }

        return $doctrineForeignKey;
    }

    /**
     * Get foreign key name.
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
     * Get doctrine table.
     *
     * @param string $table
     *
     * @return \Doctrine\DBAL\Schema\Table
     */
    public static function getDoctrineTable($table)
    {
        $table = trim($table);

        if (! static::tableExists($table)) {
            throw SchemaException::tableDoesNotExist($table);
        }

        return static::manager()->listTableDetails($table);
    }

    /**
     * Get all foreignkeys as an array.
     *
     * @return array
     */
    public static function toArray(DoctrineForeignKey $foreignKey)
    {
        return [
            'name' => $foreignKey->getName(),
            'localTable' => $foreignKey->getLocalTableName(),
            'localColumns' => $foreignKey->getLocalColumns(),
            'foreignTable' => $foreignKey->getForeignTableName(),
            'foreignColumns' => $foreignKey->getForeignColumns(),
            'options' => $foreignKey->getOptions(),
        ];
    }
}
