<?php

use CodexShaper\DBM\Facades\Manager;
use Illuminate\Database\Seeder;

class DatabaseUserPerissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user_model        = config('dbm.user.model');
        $user_table        = config('dbm.user.table');
        $user_local_key    = config('dbm.user.local_key');
        $user_display_name = config('dbm.user.display_name');
        $where             = config('dbm.user.where');

        $user = $user_model::where($where)->first();

        $localModel = Manager::model($user_model, $user_table)
            ->where($user_local_key, $user->{$user_local_key})
            ->first();
        $permissions = Manager::Permission()->all();
        Manager::Object()
            ->setManyToManyRelation(
                $localModel,
                Manager::Permission(),
                'dbm_user_permissions',
                'user_id',
                'dbm_permission_id'
            )
            ->belongs_to_many()
            ->sync($permissions);
    }
}
