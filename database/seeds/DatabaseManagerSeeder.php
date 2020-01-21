<?php

use Illuminate\Database\Seeder;

class DatabaseManagerSeeder extends Seeder
{
    protected $seedersPath = __DIR__.'/../../database/seeds/';

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $seeds = [
            'DatabasePermissionSeeder',
        ];

        foreach ($seeds as $class) {
            $file = $this->seedersPath.$class.'.php';
            if (file_exists($file) && ! class_exists($class)) {
                require_once $file;
            }
            with(new $class())->run();
        }
    }
}
