<?php

namespace CodexShaper\DBM\Database\Types;

use CodexShaper\DBM\Database\Schema\SchemaManager;
use Doctrine\DBAL\Types\Type as DoctrineType;

abstract class Type extends DoctrineType
{
    /**
     * Get type name.
     *
     * @return string
     */
    public function getName()
    {
        return static::NAME;
    }

    /**
     * Register Custom type.
     *
     * @return void
     */
    public static function registerCustomTypes()
    {
        $platform = SchemaManager::getInstance()->getDatabasePlatform();
        $platformName = ucfirst($platform->getName());

        $customTypes = array_merge(
            static::getCustomTypes('Common'),
            static::getCustomTypes($platformName)
        );

        foreach ($customTypes as $type) {
            $name = $type::NAME;

            if (static::hasType($name)) {
                static::overrideType($name, $type);
            } else {
                static::addType($name, $type);
            }

            $dbType = defined("{$type}::DBTYPE") ? $type::DBTYPE : $name;

            $platform->registerDoctrineTypeMapping($dbType, $name);
        }
    }

    /**
     * Get custom types.
     *
     * @param string $platformName
     *
     * @return array
     */
    protected static function getCustomTypes($platformName)
    {
        $customPlatformDir = __DIR__.DIRECTORY_SEPARATOR.$platformName.DIRECTORY_SEPARATOR;

        $customTypes = [];

        foreach (glob($customPlatformDir.'*.php') as $file) {
            $className = basename($file, '.php');
            $customTypes[] = __NAMESPACE__.'\\'.$platformName.'\\'.$className;
        }

        return $customTypes;
    }

    /**
     * Get Type categories.
     *
     * @return array
     */
    public static function getTypeCategories()
    {
        return [
            'numbers' => [
                'boolean',
                'tinyint',
                'smallint',
                'mediumint',
                'integer',
                'int',
                'bigint',
                'decimal',
                'numeric',
                'money',
                'float',
                'real',
                'double',
                'double precision',
            ],
            'strings' => [
                'char',
                'character',
                'varchar',
                'character varying',
                'string',
                'guid',
                'uuid',
                'tinytext',
                'text',
                'mediumtext',
                'longtext',
                'tsquery',
                'tsvector',
                'xml',
            ],
            'datetime' => [
                'date',
                'datetime',
                'year',
                'time',
                'timetz',
                'timestamp',
                'timestamptz',
                'datetimetz',
                'dateinterval',
                'interval',
            ],
            'lists' => [
                'enum',
                'set',
                'simple_array',
                'array',
                'json',
                'jsonb',
                'json_array',
            ],
            'binary' => [
                'bit',
                'bit varying',
                'binary',
                'varbinary',
                'tinyblob',
                'blob',
                'mediumblob',
                'longblob',
                'bytea',
            ],
            'network' => [
                'cidr',
                'inet',
                'macaddr',
                'txid_snapshot',
            ],
            'geometry' => [
                'geometry',
                'point',
                'linestring',
                'polygon',
                'multipoint',
                'multilinestring',
                'multipolygon',
                'geometrycollection',
            ],
            'objects' => ['object'],
        ];
    }
}
