<?php

namespace CodexShaper\DBM\Models;

use CodexShaper\DBM\Models\MenuSetting;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $table = 'menu_items';
    //
    protected $fillable = [
        'title', 'slug', 'order', 'parent_id',
    ];

    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order', 'asc');
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
