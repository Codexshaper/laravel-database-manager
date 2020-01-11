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
        $permission = DBM::Permission()::insert([
            ['name' => 'Browse', 'slug' => 'browse', 'prefix' => 'permission'],
            ['name' => 'Create', 'slug' => 'create', 'prefix' => 'permission'],
            ['name' => 'Update', 'slug' => 'update', 'prefix' => 'permission'],
            ['name' => 'Delete', 'slug' => 'delete', 'prefix' => 'permission'],
        ]);

        $database = DBM::Permission()::insert([
            ['name' => 'Browse', 'slug' => 'browse', 'prefix' => 'database'],
            ['name' => 'Create', 'slug' => 'create', 'prefix' => 'database'],
            ['name' => 'Update', 'slug' => 'update', 'prefix' => 'database'],
            ['name' => 'Delete', 'slug' => 'delete', 'prefix' => 'database'],
        ]);

        $crud = DBM::Permission()::insert([
            ['name' => 'Browse', 'slug' => 'browse', 'prefix' => 'crud'],
            ['name' => 'Create', 'slug' => 'create', 'prefix' => 'crud'],
            ['name' => 'Update', 'slug' => 'update', 'prefix' => 'crud'],
            ['name' => 'Delete', 'slug' => 'delete', 'prefix' => 'crud'],
        ]);

        $relationship = DBM::Permission()::insert([
            ['name' => 'Browse', 'slug' => 'browse', 'prefix' => 'relationship'],
            ['name' => 'Create', 'slug' => 'create', 'prefix' => 'relationship'],
            ['name' => 'Update', 'slug' => 'update', 'prefix' => 'relationship'],
            ['name' => 'Delete', 'slug' => 'delete', 'prefix' => 'relationship'],
        ]);

        $record = DBM::Permission()::insert([
            ['name' => 'Browse', 'slug' => 'browse', 'prefix' => 'record'],
            ['name' => 'Create', 'slug' => 'create', 'prefix' => 'record'],
            ['name' => 'Update', 'slug' => 'update', 'prefix' => 'record'],
            ['name' => 'Delete', 'slug' => 'delete', 'prefix' => 'record'],
        ]);

        $backup = DBM::Permission()::insert([
            ['name' => 'Browse', 'slug' => 'browse', 'prefix' => 'backup'],
            ['name' => 'Create', 'slug' => 'create', 'prefix' => 'backup'],
            ['name' => 'Restore', 'slug' => 'restore', 'prefix' => 'backup'],
            ['name' => 'Delete', 'slug' => 'delete', 'prefix' => 'backup'],
            ['name' => 'Download', 'slug' => 'download', 'prefix' => 'backup'],
        ]);
    }
}
