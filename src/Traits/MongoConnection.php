<?php

namespace CodexShaper\DBM\Traits;

use Illuminate\Support\Facades\DB;
use MongoDB\Client;

trait MongoConnection
{
    protected static $connection;
    protected $admin = 'admin';

    public function __construct()
    {
        if (!self::$connection = DB::connection()->getMongoClient()) {
            $host    = config('database.connections.mongodb.host');
            $port    = config('database.connections.mongodb.port');
            $options = config('database.connections.mongodb.options');
            $auth_db = config('database.connections.mongodb.options.database') ? config('database.connections.mongodb.options.database') : null;
            $dsn     = config('database.connections.mongodb.dsn');

            if (!$dsn) {
                $dsn = 'mongodb://' . $host . ':' . $port . ($auth_db ? "/" . $auth_db : '');
            }

            self::$connection = new Client($dsn);
        }

        $this->admin = config('database.connections.mongodb.options.database', 'admin');

    }

    public function getMongoClient()
    {
        if (!self::$connection) {
            new self();
        }

        return self::$connection;
    }
}
