<?php

namespace CodexShaper\DBM\Database\Drivers\MongoDB;

use CodexShaper\DBM\Database\Drivers\MongoDB\Collection;

class Field
{

    public static function create($collection, $clousure)
    {
        $clousure(new Collection($collection));
    }
}
