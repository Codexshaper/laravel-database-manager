<?php

namespace CodexShaper\DBM\Commands;

use CodexShaper\Dumper\Contracts\Dumper;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Input\InputOption;

class DatabaseRestore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dbm:restore
                            {--p|path=}
                            {--f|file=}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore Database';

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
    public function handle(Filesystem $filesystem, Dumper $dumper)
    {
        $this->info('Restoring Database');

        $driver = dbm_driver();
        $hostname = config('database.connections.'.$driver.'.host', '127.0.0.1');
        $port = config('database.connections.'.$driver.'.port', '3306');
        $database = config('database.connections.'.$driver.'.database', 'dbm');
        $username = config('database.connections.'.$driver.'.username', 'root');
        $password = config('database.connections.'.$driver.'.password', '');

        $directory = 'backups'.DIRECTORY_SEPARATOR.$driver;

        if ($this->option('path') != null) {
            $path = $this->option('path');
        } elseif ($this->option('file') != null) {
            $path = $directory.DIRECTORY_SEPARATOR.$this->option('file');
        } else {
            $files = array_reverse(Storage::files($directory));
            $path = $files[0];
        }

        $filePath = storage_path('app').DIRECTORY_SEPARATOR.$path;
        $isCompress = config('dbm.backup.compress', false);
        $compressBinaryPath = config('dbm.backup.compress_binary_path', '');
        $compressCommand = config('dbm.backup.uncompress_command', 'gunzip');
        $compressExtension = config('dbm.backup.compress_extension', '.gz');
        $dumpBinaryPath = config('dbm.backup.'.$driver.'.binary_path', '');

        $dumper->setHost($hostname)
            ->setPort($port)
            ->setDbName($database)
            ->setUserName($username)
            ->setPassword($password);

        try {
            switch ($driver) {
                case 'mongodb':
                    $dsn = config('dbm.backup.mongodb.dsn', '');
                    if (! empty($dsn)) {
                        $dumper->setUri($dsn);
                    }
                    break;
            }

            if ($isCompress) {
                $dumper->useCompress($compressCommand, $compressExtension, $compressBinaryPath);
            }
            $dumper->setCommandBinaryPath($dumpBinaryPath)
                ->setRestorePath($filePath)
                ->restore();

            $this->info('Restored Completed');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), 1);
        }
    }
}
