<?php

use CodexShaper\DBM\Facades\Manager as DBM;
use Illuminate\Database\Seeder;

class DatabaseMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu = DBM::Menu()->where('slug', 'admin')->first();

        if (! $menu) {
            $order = DBM::Menu()->max('order');
            $menu = DBM::Menu();
            $menu->name = 'Admin';
            $menu->slug = Str::slug('Admin');
            $menu->url = '/admin';
            $menu->order = $order + 1;
            $menu->save();
        }

        foreach ($this->getItems() as $item) {
            if (! DBM::MenuItem()->where('slug', Str::slug($item['slug']))->first()) {
                $itemOrder = DBM::MenuItem()->max('order');
                $menuItem = DBM::MenuItem();
                $menuItem->menu_id = $menu->id;
                $menuItem->title = $item['title'];
                $menuItem->slug = Str::slug($item['slug']);
                $menuItem->url = $item['url'];
                if ($item['parent']) {
                    $parentItem = DBM::MenuItem()->where('slug', $item['parent'])->first();
                    $menuItem->parent_id = $parentItem->id;
                }
                $menuItem->order = $itemOrder + 1;
                $menuItem->route = $item['route'];
                $menuItem->params = $item['params'];
                $menuItem->middleware = $item['middleware'];
                $menuItem->controller = $item['controller'];
                $menuItem->target = $item['target'];
                $menuItem->icon = $item['icon'];
                $menuItem->custom_class = $item['custom_class'];
                $menuItem->save();
            }
        }
    }

    public function getItems()
    {
        return [
            'database' => [
                'title' => 'Database',
                'slug' => 'database',
                'url' => null,
                'parent' => null,
                'route' => null,
                'params' => null,
                'controller' => null,
                'middleware' => null,
                'target' => '_self',
                'icon' => '<i class="fas fa-database"></i>',
                'custom_class' => null,
            ],
            'table' => [
                'title' => 'Table',
                'slug' => 'table',
                'url' => '/database',
                'parent' => 'database',
                'route' => 'database',
                'params' => null,
                'controller' => '\CodexShaper\DBM\Http\Controllers\DatabaseController@index',
                'middleware' => null,
                'target' => '_self',
                'icon' => '<i class="fas fa-table"></i>',
                'custom_class' => null,
            ],
            'crud' => [
                'title' => 'Crud',
                'slug' => 'crud',
                'url' => '/database/crud',
                'parent' => 'database',
                'route' => 'crud',
                'params' => null,
                'controller' => '\CodexShaper\DBM\Http\Controllers\CrudController@index',
                'middleware' => null,
                'target' => '_self',
                'icon' => '<i class="fas fa-database"></i>',
                'custom_class' => null,
            ],
            'permission' => [
                'title' => 'Permission',
                'slug' => 'permission',
                'url' => '/permission',
                'parent' => 'database',
                'route' => 'permission',
                'params' => null,
                'controller' => '\CodexShaper\DBM\Http\Controllers\PermissionController@index',
                'middleware' => null,
                'target' => '_self',
                'icon' => '<i class="fas fa-user-lock"></i>',
                'custom_class' => null,
            ],
            'backup' => [
                'title' => 'Backup',
                'slug' => 'backup',
                'url' => '/backup',
                'parent' => 'database',
                'route' => 'backup',
                'params' => null,
                'controller' => '\CodexShaper\DBM\Http\Controllers\BackupController@index',
                'middleware' => null,
                'target' => '_self',
                'icon' => '<i class="fas fa-sync"></i>',
                'custom_class' => null,
            ],
        ];
    }
}
