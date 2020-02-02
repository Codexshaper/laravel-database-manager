<?php

namespace CodexShaper\DBM;

use CodexShaper\DBM\Commands\DatabaseAdmin;
use CodexShaper\DBM\Commands\DatabaseBackup;
use CodexShaper\DBM\Commands\DatabaseRestore;
use CodexShaper\DBM\Commands\DatabaseSeed;
use CodexShaper\DBM\Commands\InstallDatabaseManager;
use CodexShaper\DBM\MongoDB\Passport\AuthCode;
use CodexShaper\DBM\MongoDB\Passport\Bridge\RefreshToken;
use CodexShaper\DBM\MongoDB\Passport\Bridge\RefreshTokenRepository;
use CodexShaper\DBM\MongoDB\Passport\Client;
use CodexShaper\DBM\MongoDB\Passport\PersonalAccessClient;
use CodexShaper\DBM\MongoDB\Passport\Token;
use CodexShaper\Dumper\Contracts\Dumper;
use CodexShaper\Dumper\Drivers\MongoDumper;
use CodexShaper\Dumper\Drivers\MysqlDumper;
use CodexShaper\Dumper\Drivers\PgsqlDumper;
use CodexShaper\Dumper\Drivers\SqliteDumper;
use Illuminate\Support\ServiceProvider;

class ManagerServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'dbm');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $database = $this->app->config['database'];
        // Bind Dumper to backup and and restore
        $this->app->bind(Dumper::class, function ($app) use ($database) {
            $connection = $database['default'];
            $options = [
                'host' => $database['connections'][$connection]['host'] ?? '',
                'port' => $database['connections'][$connection]['port'] ?? 3306,
                'dbName' => $database['connections'][$connection]['database'] ?? '',
                'username' => $database['connections'][$connection]['username'] ?? 'root',
                'password' => $database['connections'][$connection]['password'] ?? '',
            ];
            switch ($connection) {
                case 'mysql':
                    return new MysqlDumper($options);
                    break;
                case 'sqlite':
                    return new SqliteDumper($options);
                    break;
                case 'pgsql':
                    return new PgsqlDumper($options);
                    break;
                case 'mongodb':
                    return new MongoDumper($options);
                    break;
            }
        });
        // Register aliases for mongoDB passport
        if ($database['default'] == 'mongodb') {
            if (class_exists('Illuminate\Foundation\AliasLoader')) {
                $loader = \Illuminate\Foundation\AliasLoader::getInstance();
                $loader->alias('Laravel\Passport\AuthCode', AuthCode::class);
                $loader->alias('Laravel\Passport\Client', Client::class);
                $loader->alias('Laravel\Passport\Bridge\RefreshTokenRepository', RefreshTokenRepository::class);
                $loader->alias('Laravel\Passport\Bridge\RefreshToken', RefreshToken::class);
                $loader->alias('Laravel\Passport\PersonalAccessClient', PersonalAccessClient::class);
                $loader->alias('Laravel\Passport\Token', Token::class);
            } else {
                class_alias('Laravel\Passport\AuthCode', AuthCode::class);
                class_alias('Laravel\Passport\Client', Client::class);
                class_alias('Laravel\Passport\Bridge\RefreshTokenRepository', RefreshTokenRepository::class);
                class_alias('Laravel\Passport\Bridge\RefreshToken', RefreshToken::class);
                class_alias('Laravel\Passport\PersonalAccessClient', PersonalAccessClient::class);
                class_alias('Laravel\Passport\Token', Token::class);
            }
        }

        $this->mergeConfigFrom(
            __DIR__.'/../config/dbm.php', 'config'
        );
        $this->loadHelpers();
        $this->registerPublish();
        $this->registerCommands();
    }

    /**
     * Include helper files.
     *
     * @return void
     */
    protected function loadHelpers()
    {
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename) {
            require_once $filename;
        }
    }

    /**
     * Register the publishers.
     *
     * @return void
     */
    protected function registerPublish()
    {
        $publishable = [
            'dbm.config' => [
                __DIR__.'/../config/dbm.php' => config_path('dbm.php'),
            ],
            'dbm.migrations' => [
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ],
            'dbm.seeds' => [
                __DIR__.'/../database/seeds/' => database_path('seeds'),
            ],
            'dbm.views' => [
                __DIR__.'/../resources/views' => resource_path('views/vendor/dbm/views'),
            ],
            'dbm.resources' => [
                __DIR__.'/../resources' => resource_path('views/vendor/dbm'),
            ],
        ];

        foreach ($publishable as $group => $paths) {
            $this->publishes($paths, $group);
        }
    }

    /**
     * Register commands.
     *
     * @return void
     */
    private function registerCommands()
    {
        $this->commands(InstallDatabaseManager::class);
        $this->commands(DatabaseAdmin::class);
        $this->commands(DatabaseBackup::class);
        $this->commands(DatabaseRestore::class);
        $this->commands(DatabaseSeed::class);
    }
}
