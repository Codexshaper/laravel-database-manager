<?php

namespace CodexShaper\DBM;

use CodexShaper\DBM\Facades\Driver;
use CodexShaper\DBM\Models\DBM_Field;
use CodexShaper\DBM\Models\DBM_Menu;
use CodexShaper\DBM\Models\DBM_MenuItem;
use CodexShaper\DBM\Models\DBM_MongoField;
use CodexShaper\DBM\Models\DBM_MongoMenu;
use CodexShaper\DBM\Models\DBM_MongoMenuItem;
use CodexShaper\DBM\Models\DBM_MongoObject;
use CodexShaper\DBM\Models\DBM_MongoPermission;
use CodexShaper\DBM\Models\DBM_MongoTemplate;
use CodexShaper\DBM\Models\DBM_Object;
use CodexShaper\DBM\Models\DBM_Permission;
use CodexShaper\DBM\Models\DBM_Template;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Manager
{
    /**
     * Include Web routes.
     *
     * @return void
     */
    public function webRoutes()
    {
        require __DIR__.'/../routes/web.php';
    }

    /**
     * Include API routes.
     *
     * @return void
     */
    public function apiRoutes()
    {
        require __DIR__.'/../routes/api.php';
    }

    /**
     * Load assests.
     *
     * @param  string $path
     *
     * @return \Illuminate\Http\Response
     */
    public function assets($path)
    {
        $file = base_path(trim(config('dbm.resources_path'), '/').'/'.urldecode($path));

        if (File::exists($file)) {
            switch ($extension = pathinfo($file, PATHINFO_EXTENSION)) {
                case 'js':
                    $mimeType = 'text/javascript';
                    break;
                case 'css':
                    $mimeType = 'text/css';
                    break;
                default:
                    $mimeType = File::mimeType($file);
                    break;
            }

            if (! $mimeType) {
                $mimeType = 'text/plain';
            }

            $response = Response::make(File::get($file), 200);
            $response->header('Content-Type', $mimeType);
            $response->setSharedMaxAge(31536000);
            $response->setMaxAge(31536000);
            $response->setExpires(new \DateTime('+1 year'));

            return $response;
        }

        return response('', 404);
    }

    /**
     * Get Model Namespace.
     *
     * @return string
     */
    public function getModelNamespace()
    {
        return trim(config('dbm.modal_namespace', App::getNamespace()), '\\');
    }

    /**
     * Get model name with namespace.
     *
     * @param string $className
     *
     * @return string
     */
    public function generateModelName($className)
    {
        return static::getModelNamespace().'\\'.ucfirst(Str::singular($className));
    }

    /**
     * Make new model.
     *
     * @param string $model
     * @param string $table
     *
     * @return void
     */
    public function makeModel($model, $table)
    {
        try {
            $partials = explode('\\', $model);
            $className = array_pop($partials);
            $namespace = implode('\\', $partials);

            $app = array_shift($partials);
            $directory = implode(DIRECTORY_SEPARATOR, $partials);
            if (strtolower($app) != 'app') {
                $namespace = 'App\\'.$namespace;
                $directory = $app.DIRECTORY_SEPARATOR.$directory;
            }

            $path = app_path().DIRECTORY_SEPARATOR.$directory;

            if (! File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
            }

            $contents = "<?php\n\n";
            $contents .= 'namespace '.$namespace.";\n\n";
            if (Driver::isMongoDB()) {
                $contents .= "use Jenssegers\Mongodb\Eloquent\Model;\n\n";
            } else {
                $contents .= "use Illuminate\Database\Eloquent\Model;\n\n";
            }
            $contents .= 'class '.$className." extends Model\n";
            $contents .= "{\n\n";
            if (Driver::isMongoDB()) {
                $contents .= "\tprotected \$collection = '".$table."';\n";
            } else {
                $contents .= "\tprotected \$table = '".$table."';\n";
            }

            // $content .= "\tpublic \$timestamps = false;\n";
            $contents .= "}\n";

            $filesystem = new Filesystem;
            $filesystem->put($path.DIRECTORY_SEPARATOR.$className.'.php', $contents);
        } catch (\Exception $e) {
            throw new \Exception('There has an error when create model. The error is :'.$e->getMessage(), 1);
        }
    }

    /**
     * Make new controller.
     *
     * @param string $controller
     *
     * @return void
     */
    public function makeController($controller)
    {
        try {
            Artisan::call('make:controller', [
                'name' => $controller,
            ]);
        } catch (\Exception $e) {
            throw new \Exception('There has an error when create Controller. The error is :'.$e->getMessage(), 1);
        }
    }

    /**
     * Create new model instance.
     *
     * @param string $model
     * @param string|null $table
     *
     * @return object
     */
    public function model($model, $table = null)
    {
        if ($table == null) {
            return new $model;
        }

        return (new $model)->setTable($table);
    }

    /**
     * Create new model instance.
     *
     * @return \CodexShaper\DBM\Models\DBM_MongoObject|\CodexShaper\DBM\Models\DBM_Object
     */
    public function Object()
    {
        if (Driver::isMongoDB()) {
            return new DBM_MongoObject();
        }

        return new DBM_Object;
    }

    /**
     * Create new model instance.
     *
     * @return \CodexShaper\DBM\Models\DBM_MongoField|\CodexShaper\DBM\Models\DBM_Field
     */
    public function Field()
    {
        if (Driver::isMongoDB()) {
            return new DBM_MongoField;
        }

        return new DBM_Field;
    }

    /**
     * Create new model instance.
     *
     * @return \CodexShaper\DBM\Models\DBM_MongoPermission|\CodexShaper\DBM\Models\DBM_Permission
     */
    public function Permission()
    {
        if (Driver::isMongoDB()) {
            return new DBM_MongoPermission;
        }

        return new DBM_Permission;
    }

    /**
     * Create new model instance.
     *
     * @return \CodexShaper\DBM\Models\DBM_MongoTemplate|\CodexShaper\DBM\Models\DBM_Template
     */
    public function Template()
    {
        if (Driver::isMongoDB()) {
            return new DBM_MongoTemplate;
        }

        return new DBM_Template;
    }

    /**
     * Create new model instance.
     *
     * @return \CodexShaper\DBM\Models\DBM_MongoMenu|\CodexShaper\DBM\Models\DBM_Menu
     */
    public function Menu()
    {
        if (Driver::isMongoDB()) {
            return new DBM_MongoMenu;
        }

        return new DBM_Menu;
    }

    /**
     * Create new model instance.
     *
     * @return \CodexShaper\DBM\Models\DBM_MongoMenuItem|\CodexShaper\DBM\Models\DBM_MenuItem
     */
    public function MenuItem()
    {
        if (Driver::isMongoDB()) {
            return new DBM_MongoMenuItem;
        }

        return new DBM_MenuItem;
    }

    /**
     * Get all templates.
     *
     * @return array
     */
    public function templates()
    {
        $templates = static::Template()->get();
        $newTemplates = [];

        foreach ($templates as $template) {
            $newTemplates[] = (object) [
                'name' => $template->name,
                'oldName' => $template->old_name,
                'type' => [
                    'name' => $template->type,
                ],
                'notnull' => $template->notnull,
                'unsigned' => $template->unsigned,
                'autoincrement' => $template->auto_increment,
                'default' => $template->default,
                'length' => $template->length,
                'index' => ($template->index != null) ? $template->index : '',
            ];
        }

        return $newTemplates;
    }

    /**
     * Get file path prefix.
     *
     * @param string @driver
     *
     * @return string
     */
    public function getPathPrefix($driver = 'local')
    {
        return trim(Storage::disk($driver)->getDriver()->getAdapter()->getPathPrefix(), DIRECTORY_SEPARATOR);
    }

    /**
     * Get all templates.
     *
     * @return \Illuminate\Support\Collection
     */
    public function userPermissions()
    {
        $user = Auth::user();

        return self::Object()
            ->setManyToManyRelation(
                $user,
                static::Permission(),
                'dbm_user_permissions',
                'user_id',
                'dbm_permission_id'
            )
            ->belongs_to_many;
    }

    /**
     * Check user loggedin or not.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function isLoggedIn()
    {
        if (Auth::guest()) {
            return Route::has('login') ? redirect(route('login')) : Response::view('dbm::errors.404', [], 404);
        }

        return true;
    }

    /**
     * Check user permission.
     *
     * @param string $prefix
     * @param string $slug
     *
     * @return string
     */
    public function checkPermission($prefix, $slug)
    {
        if (Auth::guest()) {
            return 'not_logged_in';
        }

        $user_model = config('dbm.auth.user.model');
        $user_table = config('dbm.auth.user.table');
        $user_local_key = config('dbm.auth.user.local_key');
        $user_display_name = config('dbm.auth.user.display_name');

        $permissions = $this->userPermissions();

        foreach ($permissions as $permission) {
            if ($permission->prefix == $prefix && $permission->slug == $slug) {
                return 'authorized';
            }
        }

        return 'not_authorized';
    }

    /**
     * Check user aurization.
     *
     * @param string $permission
     *
     * @return \Illuminate\Http\JsonResponse|true
     */
    public function authorize($permission)
    {
        $permission = explode('.', $permission);

        $prefix = $permission[0];
        $slug = $permission[1];

        switch ($this->checkPermission($prefix, $slug)) {
            case 'not_logged_in':
                return response()->json([
                    'success' => false,
                    'url' => route('login'),
                ]);
                break;

            case 'not_authorized':
                return response()->json([
                    'success' => false,
                    'errors' => ["You don't have permission to ".$slug.' '.$prefix],
                ], 401);
                break;
            case 'authorized':
                return true;
                break;
        }
    }
}
