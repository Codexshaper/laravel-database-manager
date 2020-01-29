<?php

namespace CodexShaper\DBM\Models;

use CodexShaper\DBM\Traits\Relationships;
use Jenssegers\Mongodb\Eloquent\Model;

class DBM_MongoMenu extends Model
{
    use Relationships;
    //
    protected $collection = 'menus';

    public function items()
    {
        return $this->hasMany(MenuItem::class, 'menu_id');
    }
}
