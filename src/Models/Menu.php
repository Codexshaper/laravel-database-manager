<?php

namespace CodexShaper\DBM\Models;

use CodexShaper\DBM\Models\MenuItem;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    //
    protected $table = 'menus';

    public function items()
    {
        return $this->hasMany(MenuItem::class, 'menu_id');
    }
}
