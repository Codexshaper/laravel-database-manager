<?php

namespace CodexShaper\DBM\Database\Drivers\MongoDB;

class Field
{
    /**
     * Create MongoDB collection field.
     *
     * @param string $collection
     *
     * @return  \CodexShaper\DBM\Database\Drivers\MongoDB\Collection
     */
    public static function create($collection, $clousure)
    {
        $clousure(new Collection($collection));
    }
}
