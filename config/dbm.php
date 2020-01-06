<?php
return [
    'prefix'               => '',
    'namespace'            => '\CodexShaper\DBM',
    'controller_namespace' => '\CodexShaper\DBM\Http\Controllers',
    'resources_path'       => 'package/laravel-database-manager/publishable/assets/',
    'views'                => 'package/laravel-database-manager/publishable/views',
    'modal_namespace'      => 'App\\\\',
    'filesystem'           => [
        'random_length' => 32,
    ],
    'backup'               => [
        'mysql'                => [
            'binary_path' => "c:\\xampp\\mysql\\bin\\",
        ],
        'pgsql'                => [
            // 'binary_path' => "C:\\pgsql\\bin\\",
        ],
        'mongodb'              => [
            "dsn" => "mongodb+srv://maab:Abuahsan91@laravel-mongodb-t5jhc.mongodb.net/laravel-database-manager",
        ],
        'dir'                  => 'backups',
        'compress'             => true,
        'compress_binary_path' => "",
        'compress_extension'   => ".gz",
        'compress_command'     => "gzip",
        'uncompress_command'   => "gunzip",
        'debug'                => true,
    ],
    'auth'                 => [
        "token" => [
            "expiry" => 24 * 60 * 60 * 1000, // 24 hours as a milliseconds
        ],
    ],
    'user'                 => [
        'model'        => 'App\\User',
        'table'        => 'users',
        'local_key'    => '_id', // MongoDB
        // 'local_key'    => 'id', // Others
        'display_name' => 'name',
        'where'        => ['email' => 'admin@admin.com'],
    ],
    'permission'           => [

    ],
];
