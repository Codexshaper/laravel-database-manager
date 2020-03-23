<?php

namespace CodexShaper\DBM\Commands;

use CodexShaper\DBM\ManagerServiceProvider;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;

class InstallDatabaseManager extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'dbm:install';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Laravel Database Manager';
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
        $this->info('Publishing the Database Manager assets, database, and config files');
        // Publish only relevant resources on install
        $tags = ['dbm.config'];
        $this->call('vendor:publish', ['--provider' => ManagerServiceProvider::class, '--tag' => $tags]);
        // Create storage link
        $this->info('Generate storage link');
        $this->call('storage:link');
        // Migrate Database
        $this->info('Migrating the database tables into your application');
        $this->call('migrate', ['--force' => $this->option('force')]);
        //Install Passport
        $this->info('Install Passport');
        $this->call('passport:install', ['--force' => $this->option('force')]);

        $this->info('Dumping the autoloaded files and reloading all new files');
        $composer = $this->findComposer();
        $process = Process::fromShellCommandline($composer.' dump-autoload');
        $process->setTimeout(null); // Setting timeout to null to prevent installation from stopping at a certain point in time
        $process->setWorkingDirectory(base_path())->run();

        // Modify auth api driver token to passport
        $auth_config_contents = $filesystem->get(base_path('config/auth.php'));
        $auth_config_contents = str_replace('\'token\'', '\'passport\'', $auth_config_contents);
        $filesystem->put(
            base_path('config/auth.php'),
            $auth_config_contents
        );

        // Load Custom Database Manager routes into application's 'routes/web.php'
        $this->info('Adding Database Manager routes');
        $web_routes_contents = $filesystem->get(base_path('routes/web.php'));
        $api_routes_contents = $filesystem->get(base_path('routes/api.php'));
        if (false === strpos($web_routes_contents, 'DBM::webRoutes();')) {
            $filesystem->append(
                base_path('routes/web.php'),
                "\n\nDBM::webRoutes();\n"
            );
        }
        if (false === strpos($api_routes_contents, 'DBM::apiRoutes();')) {
            $filesystem->append(
                base_path('routes/api.php'),
                "\n\nDBM::apiRoutes();\n"
            );
        }

        $this->info('Seeding...');
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
