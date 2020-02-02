<?php

return [

    /*
    |-------------------------------------------------------------
    | Base Path
    |-------------------------------------------------------------
    |
    | Base URL
    | by default root path. You can add base path /path/to
    |
     */

    'base_path'               => '',

    /*
    |-------------------------------------------------------------
    | Prefix
    |-------------------------------------------------------------
    |
    | Set custom URL prefix
    | by default /database . You can add prefix before /database
    |
     */

    'prefix'               => 'database',

    /*
    |-------------------------------------------------------------
    | Database Driver
    |-------------------------------------------------------------
    |
    |
     */

    'driver'               => 'mysql',

    /*
    |-------------------------------------------------------------
    | Global namespace
    |-------------------------------------------------------------
    |
    | Set custom global namespace
    |
     */

    'namespace'            => '\CodexShaper\DBM',

    /*
    |-------------------------------------------------------------
    | Controller namespace
    |-------------------------------------------------------------
    |
    | Set custom namespace for controller if you want to override deafults controllers
    |
     */

    'controller_namespace' => '\CodexShaper\DBM\Http\Controllers',

    /*
    |-------------------------------------------------------------
    | Model namespace
    |-------------------------------------------------------------
    |
    | Set custom namespace for models.
    | It will create new models and load existsing models from this namespace
    |
     */

    'modal_namespace'      => 'App\\\\',

    /*
    |-------------------------------------------------------------
    | Resource path
    |-------------------------------------------------------------
    |
    | Set custom assests path so that you can load your own assets
    |
     */

    'resources_path'       => 'vendor/codexshaper/laravel-database-manager/publishable/assets/',

    /*
    |-------------------------------------------------------------
    | Views Path
    |-------------------------------------------------------------
    |
    | Set custom assests path so that you can load your own views
    |
     */

    'views'                => 'vendor/codexshaper/laravel-database-manager/publishable/views',

    /*
    |-------------------------------------------------------------
    | Migration Path
    |-------------------------------------------------------------
    |
    | Set custom assests path so that you can load your own views
    |
     */

    'migrations'            => 'vendor/codexshaper/laravel-database-manager/database/migrations',

    /*
    |-------------------------------------------------------------
    | Flesystem
    |-------------------------------------------------------------
    |
    | Set filesystem config
    |
     */

    'filesystem'           => [
        'dir'           => 'public/dbm',
        'random_length' => 32,
    ],

    /*
    |-------------------------------------------------------------
    | Authentication
    |-------------------------------------------------------------
    |
    | Set auth config
    |
     */

    'auth'                 => [
        'token' => [
            'expiry' => 24 * 60 * 60 * 1000, // 24 hours as a milliseconds
        ],
        'user'  => [
            'model'        => 'App\\User',
            'table'        => 'users',
            'local_key'    => '_id', // MongoDB
            // 'local_key'    => 'id', // Others
            'display_name' => 'name',
        ],
    ],

    /*
    |-------------------------------------------------------------
    | CRUD
    |-------------------------------------------------------------
    |
    | Set crud config
    |
     */

    'crud'                 => [
        // Set record
        'record' => [
            'is_modal' => true,
        ],
    ],

    /*
    |-------------------------------------------------------------
    | Backup
    |-------------------------------------------------------------
    |
    | Here you can set backup config
    |
     */

    'backup'               => [
        // Mysql
        'mysql'                => [
            'binary_path' => '', // c:\\xampp\\mysql\\bin\\
        ],
        // Sqlite 3
        'sqlite'               => [
            'binary_path' => '', // C:\\sqlite3\\
        ],
        // Postgree Sql
        'pgsql'                => [
            'binary_path' => '', // C:\\pgsql\\bin\\
        ],
        // MongoDB
        'mongodb'              => [
            'binary_path' => '', // C:\\Program Files\\MongoDB\\Server\\4.0\bin\\
            // 'dsn' => 'mongodb+srv://maab:Abuahsan91@laravel-mongodb-t5jhc.mongodb.net/laravel-database-manager',
        ],
        // Backup Directry in /storage/app/
        'dir'                  => 'backups',
        // Enable compression. By default true
        'compress'             => true,
        // Set compressor binary path to execute compression
        'compress_binary_path' => '',
        // Set compressor extension
        'compress_extension'   => '.gz',
        // Set compress command
        'compress_command'     => 'gzip',
        // Set uncompress command
        'uncompress_command'   => 'gunzip',
        // Enable debug when developemnt mode. By default false
        'debug'                => false,
    ],
    /*
    |-------------------------------------------------------------
    | Core
    |-------------------------------------------------------------
    |
    | Here you can set backup config
    |
     */
    'core'                 => [
        'tables' => [
            'dbm_objects',
            'dbm_fields',
            'dbm_permissions',
            'dbm_user_permissions',
            'dbm_collection_fields',
            'dbm_collections',
            'dbm_templates',
        ],
    ],

    /*
    |-------------------------------------------------------------
    | Collation
    |-------------------------------------------------------------
    |
    | Set default collation
    |
     */

    'collation'            => 'utf8mb4_unicode_ci',
];
