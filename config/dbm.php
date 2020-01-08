<?php
return [
    'prefix'               => '',
    'namespace'            => '\CodexShaper\DBM',
    'controller_namespace' => '\CodexShaper\DBM\Http\Controllers',
    'resources_path'       => 'vendor/codexshaper/laravel-database-manager/publishable/assets/',
    'views'                => 'vendor/codexshaper/laravel-database-manager/publishable/views',
    'modal_namespace'      => 'App\\\\',
    'filesystem'           => [
        'random_length' => 32,
    ],
    'auth'                 => [
        "token" => [
            "expiry" => 24 * 60 * 60 * 1000, // 24 hours as a milliseconds
        ],
        'user'  => [
            'model'        => 'App\\User',
            'table'        => 'users',
            'local_key'    => '_id', // MongoDB
            // 'local_key'    => 'id', // Others
            'display_name' => 'name',
            'where'        => ['email' => 'admin@admin.com'],
        ],
    ],
    'crud'                 => [
        'record' => [
            'is_modal' => true,
        ],
    ],
    'backup'               => [
        'mysql'                => [
            'binary_path' => "", // c:\\xampp\\mysql\\bin\\
        ],
        'sqlite'               => [
            'binary_path' => "", // C:\\sqlite3\\
        ],
        'pgsql'                => [
            'binary_path' => "", // C:\\pgsql\\bin\\
        ],
        'mongodb'              => [
            'binary_path' => "", // C:\\Program Files\\MongoDB\\Server\\4.0\bin\\
            // "dsn" => "mongodb+srv://maab:Abuahsan91@laravel-mongodb-t5jhc.mongodb.net/laravel-database-manager",
        ],
        'dir'                  => 'backups',
        'compress'             => true,
        'compress_binary_path' => "",
        'compress_extension'   => ".gz",
        'compress_command'     => "gzip",
        'uncompress_command'   => "gunzip",
        'debug'                => true,
    ],
];
