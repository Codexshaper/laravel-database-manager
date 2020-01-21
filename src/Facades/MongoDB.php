<?php

namespace CodexShaper\DBM\Facades;

use CodexShaper\DBM\Database\Drivers\MongoDB as MongoCleint;
use Illuminate\Support\Facades\Facade;

class MongoDB extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return MongoCleint::class;
    }
}
