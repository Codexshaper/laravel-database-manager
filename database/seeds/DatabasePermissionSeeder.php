<?php

use CodexShaper\DBM\Facades\Manager as DBM;
use Illuminate\Database\Seeder;

class DatabasePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DBM::Permission()::firstOrCreate(
            ['slug' => 'browse', 'prefix' => 'permission'],
            ['name' => 'Browse', 'slug' => 'browse', 'prefix' => 'permission']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'create', 'prefix' => 'permission'],
            ['name' => 'Create', 'slug' => 'create', 'prefix' => 'permission']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'update', 'prefix' => 'permission'],
            ['name' => 'Update', 'slug' => 'update', 'prefix' => 'permission']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'delete', 'prefix' => 'permission'],
            ['name' => 'Delete', 'slug' => 'delete', 'prefix' => 'permission']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'browse', 'prefix' => 'database'],
            ['name' => 'Browse', 'slug' => 'browse', 'prefix' => 'database']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'create', 'prefix' => 'database'],
            ['name' => 'Create', 'slug' => 'create', 'prefix' => 'database']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'update', 'prefix' => 'database'],
            ['name' => 'Update', 'slug' => 'update', 'prefix' => 'database']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'delete', 'prefix' => 'database'],
            ['name' => 'Delete', 'slug' => 'delete', 'prefix' => 'database']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'browse', 'prefix' => 'crud'],
            ['name' => 'Browse', 'slug' => 'browse', 'prefix' => 'crud']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'create', 'prefix' => 'crud'],
            ['name' => 'Create', 'slug' => 'create', 'prefix' => 'crud']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'update', 'prefix' => 'crud'],
            ['name' => 'Update', 'slug' => 'update', 'prefix' => 'crud']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'delete', 'prefix' => 'crud'],
            ['name' => 'Delete', 'slug' => 'delete', 'prefix' => 'crud']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'browse', 'prefix' => 'relationship'],
            ['name' => 'Browse', 'slug' => 'browse', 'prefix' => 'relationship']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'create', 'prefix' => 'relationship'],
            ['name' => 'Create', 'slug' => 'create', 'prefix' => 'relationship']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'update', 'prefix' => 'relationship'],
            ['name' => 'Update', 'slug' => 'update', 'prefix' => 'relationship']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'delete', 'prefix' => 'relationship'],
            ['name' => 'Delete', 'slug' => 'delete', 'prefix' => 'relationship']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'browse', 'prefix' => 'record'],
            ['name' => 'Browse', 'slug' => 'browse', 'prefix' => 'record']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'create', 'prefix' => 'record'],
            ['name' => 'Create', 'slug' => 'create', 'prefix' => 'record']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'update', 'prefix' => 'record'],
            ['name' => 'Update', 'slug' => 'update', 'prefix' => 'record']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'delete', 'prefix' => 'record'],
            ['name' => 'Delete', 'slug' => 'delete', 'prefix' => 'record']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'browse', 'prefix' => 'backup'],
            ['name' => 'Browse', 'slug' => 'browse', 'prefix' => 'backup']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'create', 'prefix' => 'backup'],
            ['name' => 'Create', 'slug' => 'create', 'prefix' => 'backup']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'restore', 'prefix' => 'backup'],
            ['name' => 'Restore', 'slug' => 'restore', 'prefix' => 'backup']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'delete', 'prefix' => 'backup'],
            ['name' => 'Delete', 'slug' => 'delete', 'prefix' => 'backup']
        );
        DBM::Permission()::firstOrCreate(
            ['slug' => 'download', 'prefix' => 'backup'],
            ['name' => 'Download', 'slug' => 'download', 'prefix' => 'backup']
        );
    }
}
