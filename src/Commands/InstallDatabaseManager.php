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
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dbm:install {mongodb?} {--force=}';
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
    protected $seedersPath = __DIR__ . '/../../database/seeds/';

    /**
     * Get Option
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
        if (file_exists(getcwd() . '/composer.phar')) {
            return '"' . PHP_BINARY . '" ' . getcwd() . '/composer.phar';
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
        $composer = $this->findComposer();
        if ($this->argument('mongodb') == 'mongodb') {
            $this->info('Installing MongoDB package');
            $process = new Process($composer . ' require jenssegers/mongodb');
            $process->setTimeout(null); // Setting timeout to null to prevent installation from stopping at a certain point in time
            $process->setWorkingDirectory(base_path())->run();
        }
        $this->info('Publishing the Database Manager assets, database, and config files');
        // Publish only relevant resources on install
        $tags = ['dbm.config'];
        $this->call('vendor:publish', ['--provider' => ManagerServiceProvider::class, '--tag' => $tags]);
        // Generate Storage Link
        $this->info('Generate storage symblink');
        $this->call('storage:link');
        // Dump autoload
        $this->info('Dumping the autoloaded files and reloading all new files');
        $process = new Process($composer . ' dump-autoload');
        $process->setTimeout(null); // Setting timeout to null to prevent installation from stopping at a certain point in time
        $process->setWorkingDirectory(base_path())->run();
        // Migrate database
        $this->info('Migrating the database tables into your application');
        $this->call('migrate', ['--force' => $this->option('force')]);
        // Install laravel passport
        $this->info('Install Passport');
        $this->call('passport:install', ['--force' => $this->option('force')]);
        // Load Custom Database Manager routes
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
        // Database seeder
        $this->info('Seeding...');
        $class = 'DatabaseManagerSeeder';
        $file  = $this->seedersPath . $class . '.php';
        if (file_exists($file) && !class_exists($class)) {
            require_once $file;
        }
        with(new $class())->run();
        $this->info('Seeding Completed');
    }
}
