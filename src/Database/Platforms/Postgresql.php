<?php

namespace CodexShaper\DBM\Database\Platforms;

use Illuminate\Support\Collection;

abstract class Postgresql extends Platform
{
    /**
     * Get Types.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getTypes(Collection $typeMapping)
    {
        $typeMapping->forget([
            'smallint',
            'serial',
            'serial4',
            'int',
            'integer',
            'bigserial',
            'serial8',
            'bigint',
            'decimal',
            'float',
            'real',
            'double',
            'double precision',
            'boolean',
            '_varchar',
            'char',
            'datetime',
            'year',
        ]);

        return $typeMapping;
    }

    public static function registerCustomTypeOptions()
    {
    }
}
