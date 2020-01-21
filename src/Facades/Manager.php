<?php

namespace CodexShaper\DBM\Facades;

use CodexShaper\DBM\Manager as DatabaseManager;
use Illuminate\Support\Facades\Facade;

class Manager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return DatabaseManager::class;
    }
}
