<?php

namespace CodexShaper\DBM\Commands;

use Illuminate\Console\Command;

class DatabaseMigrate extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'dbm:migrate';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate the Laravel Database Manager';

    /**
     * Execute the console command.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     *
     * @return void
     */
    public function handle()
    {
        $migrationPath = config('dbm.migrations', '/vendor/codexshaper/laravel-database-manager/database/migrations');
        // Migrate Database
        $this->info('Migrating the database manager tables into your application');
        $this->call('migrate', ['--path' => $migrationPath, '--force' => 'force']);
    }
}
