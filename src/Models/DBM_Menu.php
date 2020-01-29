<?php

namespace CodexShaper\DBM\Models;

use Illuminate\Database\Eloquent\Model;

class DBM_Menu extends Model
{
    //
    protected $table = 'menus';

    public function items()
    {
        return $this->hasMany(MenuItem::class, 'menu_id');
    }
}
