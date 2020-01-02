<?php

namespace CodexShaper\DBM\Database\Schema;

use Illuminate\Support\Facades\DB;

class SchemaManager
{
    protected static $schemaManager;

    public function __construct()
    {
        self::$schemaManager = DB::connection()->getDoctrineSchemaManager();
    }

    public static function getInstance()
    {
        if (!self::$schemaManager) {
            new self();
        }

        return self::$schemaManager;
    }
}
