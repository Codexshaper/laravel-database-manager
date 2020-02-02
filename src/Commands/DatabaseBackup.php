<?php

namespace CodexShaper\DBM\Commands;

use CodexShaper\DBM\Facades\Driver;
use CodexShaper\Dumper\Contracts\Dumper;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\InputOption;

class DatabaseBackup extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'dbm:backup
                        {--t|table=}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Database Backup';

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

    public function getFileName($table, $database)
    {
        $prefix = (strlen($table) > 0)
        ? 'table_'.strtolower(str_replace('-', '_', $table)).'_'
        : 'database_'.strtolower(str_replace('-', '_', $database)).'_';

        $extension = Driver::isMongoDB() ? '' : '.sql';
        $fileName = $prefix.'backup_'.date('G_a_m_d_y_h_i_s').$extension;

        if (Driver::isSqlite()) {
            $fileName = 'backup_'.date('G_a_m_d_y_h_i_s').$extension;
        }

        return $fileName;
    }

    public function backup(Dumper $dumper, array $data)
    {
        $isCompress = config('dbm.backup.compress', false);
        $isDebug = config('dbm.backup.debug', false);
        $compressBinaryPath = config('dbm.backup.compress_binary_path', '');
        $compressCommand = config('dbm.backup.compress_command', 'gzip');
        $compressExtension = config('dbm.backup.compress_extension', '.gz');
        $dumpBinaryPath = config('dbm.backup.'.$data['driver'].'.binary_path', '');
        $hostname = config('database.connections.'.$data['driver'].'.host', '127.0.0.1');
        $port = config('database.connections.'.$data['driver'].'.port', '3306');
        $database = config('database.connections.'.$data['driver'].'.database', 'dbm');
        $username = config('database.connections.'.$data['driver'].'.username', 'root');
        $password = config('database.connections.'.$data['driver'].'.password', '');

        $dumper->setHost($hostname)
            ->setPort($port)
            ->setDbName($database)
            ->setUserName($username)
            ->setPassword($password);

        switch ($data['driver']) {
            case 'mysql':
            case 'pgsql':
                if (! empty($data['table'])) {
                    $dumper->setTables($data['table']);
                }
                break;
            case 'mongodb':
                $dsn = config('dbm.backup.mongodb.dsn', '');
                if (! empty($dsn) && method_exists($dumper, 'setUri')) {
                    $dumper->setUri($dsn);
                }
                break;

        }

        if ($isCompress) {
            $dumper->setCompressBinaryPath($compressBinaryPath);
            $dumper->setCompressCommand($compressCommand);
            $dumper->setCompressExtension($compressExtension);
        }
        if ($isDebug) {
            $dumper->enableDebug();
        }
        $dumper->setCommandBinaryPath($dumpBinaryPath)
            ->setDestinationPath($data['filePath'])
            ->dump();
    }

    /**
     * Execute the console command.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     *
     * @return void
     */
    public function handle(Filesystem $filesystem, Dumper $dumper)
    {
        $this->info('Start Database Backup');

        $driver = dbm_driver();
        $database = config('database.connections.'.$driver.'.database', 'dbm');
        $table = ($this->option('table') != null) ? $this->option('table') : '';

        try {
            $directory = (config('dbm.backup.dir', 'backups') != '')
            ? DIRECTORY_SEPARATOR.config('dbm.backup.dir', 'backups')
            : '';
            $directoryPath = storage_path('app').$directory.DIRECTORY_SEPARATOR.$driver;
            $filePath = $directoryPath.DIRECTORY_SEPARATOR.$this->getFileName($table, $database);

            if (! File::isDirectory($directoryPath)) {
                File::makeDirectory($directoryPath, 0777, true, true);
            }

            $this->backup($dumper, [
                'filePath' => $filePath,
                'driver' => $driver,
                'table' => $table,
            ]);

            $this->info('Backup completed');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 1);
        }
    }
}
