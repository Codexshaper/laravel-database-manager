<?php

namespace CodexShaper\DBM\Database\Schema;

use Doctrine\DBAL\Schema\Column as DoctrineColumn;
use Doctrine\DBAL\Types\Type as DoctrineType;

class Column
{
    /**
     * Create new column.
     *
     * @param array $column
     *
     * @return \Doctrine\DBAL\Schema\Column
     */
    public static function create($column)
    {
        $name = $column['name'];
        $type = $column['type'];
        $type = ($type instanceof DoctrineType) ? $type : DoctrineType::getType(trim($type['name']));

        $options = array_diff_key($column, ['name' => $name, 'type' => $type]);

        $DoctrineColumn = new DoctrineColumn($name, $type, $options);

        return $DoctrineColumn;
    }

    /**
     * Get all columns as an array.
     *
     * @return array
     */
    public static function toArray(DoctrineColumn $column)
    {
        $type = $column->getType();

        $newColumn = $column->toArray();
        $newColumn['oldName'] = $column->getName();
        $newColumn['type'] = [
            'name' => $type->getName(),
        ];
        $newColumn['null'] = $column->getNotnull() ? 'NO' : 'YES';
        $newColumn['extra'] = $column->getAutoincrement() ? 'auto_increment' : '';
        $newColumn['composite'] = false;

        return $newColumn;
    }
}
