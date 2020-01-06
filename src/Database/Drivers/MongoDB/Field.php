<?php

namespace CodexShaper\DBM\Database\Drivers\MongoDB;

use CodexShaper\DBM\Database\Drivers\MongoDB\Collection;

class Field
{
    /**
     * Create MongoDB collection field
     *
     * @param string $collection
     * @param clousure $clousure
     *
     * @return  \CodexShaper\DBM\Database\Drivers\MongoDB\Collection
     */
    public static function create($collection, $clousure)
    {
        $clousure(new Collection($collection));
    }
}
