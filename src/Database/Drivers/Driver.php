<?php

namespace CodexShaper\DBM\Database\Drivers;

class Driver
{
    /**
     * Get connection name.
     *
     * @return string
     */
    public function getConnectionName()
    {
        return config('database.default', 'mysql');
    }

    /**
     * Check driver is MongoDB.
     *
     * @return bool
     */
    public function isMongoDB()
    {
        return (config('database.default') == 'mongodb') ? true : false;
    }

    /**
     * Check driver is Mysql.
     *
     * @return bool
     */
    public function isMysql()
    {
        return (config('database.default') == 'mysql') ? true : false;
    }

    /**
     * Check driver is Postgresql.
     *
     * @return bool
     */
    public function isPostgresql()
    {
        return (config('database.default') == 'pgsql') ? true : false;
    }

    /**
     * Check driver is Sqlsrv.
     *
     * @return bool
     */
    public function isSqlsrv()
    {
        return (config('database.default') == 'sqlsrv') ? true : false;
    }

    /**
     * Check driver is Sqlite.
     *
     * @return bool
     */
    public function isSqlite()
    {
        return (config('database.default') == 'sqlite') ? true : false;
    }
}
