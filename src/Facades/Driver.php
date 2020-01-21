<?php

namespace CodexShaper\DBM\Facades;

use CodexShaper\DBM\Database\Drivers\Driver as DatabaseDriver;
use Illuminate\Support\Facades\Facade;

class Driver extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return DatabaseDriver::class;
    }
}
