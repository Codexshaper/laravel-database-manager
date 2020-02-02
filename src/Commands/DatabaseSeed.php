<?php

namespace CodexShaper\DBM\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;

class DatabaseSeed extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'dbm:seed';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the Laravel Database Manager';
    /**
     * The database Seeder Path.
     *
     * @var string
     */
    protected $seedersPath = __DIR__.'/../../database/seeds/';

    /**
     * Get Option.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production', null],
        ];
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        if (file_exists(getcwd().'/composer.phar')) {
            return '"'.PHP_BINARY.'" '.getcwd().'/composer.phar';
        }

        return 'composer';
    }

    /**
     * Execute the console command.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     *
     * @return void
     */
    public function handle(Filesystem $filesystem)
    {
        $this->info('Database Manager Seeding...');
        // Seeding Dummy Data
        $class = 'DatabaseManagerSeeder';
        $file = $this->seedersPath.$class.'.php';
        if (file_exists($file) && ! class_exists($class)) {
            require_once $file;
        }
        with(new $class())->run();

        $this->info('Seeding Completed');
    }
}
