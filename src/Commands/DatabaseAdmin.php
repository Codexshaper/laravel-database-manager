<?php

namespace CodexShaper\DBM\Commands;

use CodexShaper\DBM\Facades\Manager;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class DatabaseAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dbm:admin {email} {action=create} {--c|column=email}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make Database Admin';

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
    public function handle()
    {
        $email = $this->argument('email');
        $column = $this->option('column');
        $permissions = Manager::Permission()->all();
        $successMessage = 'Admin Created successfully';

        if ($this->argument('action') == 'drop') {
            $permissions = [];
            $successMessage = 'Admin Deleted successfully';
        }

        $userModel = config('dbm.auth.user.model');
        $userTable = config('dbm.auth.user.table');
        $localObject = Manager::model($userModel, $userTable)
            ->where($column, $email)
            ->first();
        Manager::Object()
            ->setManyToManyRelation(
                $localObject,
                Manager::Permission(),
                'dbm_user_permissions',
                'user_id',
                'dbm_permission_id'
            )
            ->belongs_to_many()
            ->sync($permissions);
        $this->info($successMessage);
    }
}
