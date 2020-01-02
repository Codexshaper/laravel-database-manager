<?php
namespace CodexShaper\DBM\Facades;

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
        return '\CodexShaper\DBM\Database\Drivers\Driver';
    }
}
