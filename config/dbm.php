<?php
return [
    'prefix'               => '/admin',
    'namespace'            => '\CodexShaper\DBM',
    'controller_namespace' => '\CodexShaper\DBM\Http\Controllers',
    'resources_path'       => 'package/laravel-database-manager/publishable/assets/',
    'views'                => 'package/laravel-database-manager/publishable/views',
    'modal_namespace'      => 'App\\\\',
    'filesystem'           => [
        'random_length' => 32,
    ],
    'backup'               => [
        'dump_path'    => "c:\\\\xampp\\\\mysql\\\\bin\\\\mysqldump",
        'restore_path' => "c:\\\\xampp\\\\mysql\\\\bin\\\\mysql",
        'dir'          => 'backups',
    ],
    'user'                 => [
        'model'        => 'App\\User',
        'table'        => 'users',
        'local_key'    => 'id',
        'display_name' => 'name',
    ],
    'permission'           => [

    ],
];
