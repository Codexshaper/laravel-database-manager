<?php

namespace CodexShaper\DBM\Models;

use CodexShaper\DBM\Traits\Relationships;
use Jenssegers\Mongodb\Eloquent\Model;

class DBM_MongoMenuItem extends Model
{
    use Relationships;

    protected $collection = 'menu_items';
    //
    protected $fillable = [
        'title',
        'slug',
        'order',
        'parent_id',
    ];

    protected $cast = [
        'params' => 'array',
    ];

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('order', 'asc');
    }

    // recursive, loads all descendants
    public function childrens()
    {
        return $this->children()->with('childrens');
    }

    public function settings()
    {
        return $this->hasMany(MenuSetting::class, 'menu_id');
    }
}
