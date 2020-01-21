<?php

namespace CodexShaper\DBM\Database\Schema;

use Illuminate\Support\Facades\DB;

class SchemaManager
{
    protected static $schemaManager;

    /**
     * Create SchemaManager instance.
     *
     * @return void
     */
    public function __construct()
    {
        self::$schemaManager = DB::connection()->getDoctrineSchemaManager();
    }

    /**
     * get SchemaManager instance.
     *
     * @return \Doctrine\DBAL\Schema\AbstractSchemaManager
     */
    public static function getInstance()
    {
        if (! self::$schemaManager) {
            new self();
        }

        return self::$schemaManager;
    }
}
