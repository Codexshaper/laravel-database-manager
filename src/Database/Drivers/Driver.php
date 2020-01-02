<?php

namespace CodexShaper\DBM\Database\Drivers;

class Driver
{

    public function getConnectionName()
    {
        return config('database.default', 'mysql');
    }

    public function isMongoDB()
    {
        return (config('database.default') == 'mongodb') ? true : false;
    }

    public function isMysql()
    {
        return (config('database.default') == 'mysql') ? true : false;
    }

    public function isPostgresql()
    {
        return (config('database.default') == 'pgsql') ? true : false;
    }

    public function isSqlsrv()
    {
        return (config('database.default') == 'sqlsrv') ? true : false;
    }

    public function isSqlite()
    {
        return (config('database.default') == 'sqlite') ? true : false;
    }
}
