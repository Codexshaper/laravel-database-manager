<?php

namespace CodexShaper\DBM\Http\Controllers;

use CodexShaper\DBM\Database\Schema\Table;
use CodexShaper\DBM\Facades\Manager as DBM;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CrudController extends Controller
{
    /**
     * Load all crud tables.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        return view('dbm::app');
    }

    /**
     * Create|Update CRUD.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeOrUpdate(Request $request)
    {
        if ($request->ajax()) {
            $table = $request->object;
            $columns = $request->fields;
            $permission = $request->isCrudExists ? 'update' : 'create';

            if (($response = DBM::authorize('crud.'.$permission)) !== true) {
                return $response;
            }

            if (($response = $this->makeModel($table)) !== true) {
                return $response;
            }

            if (! class_exists($table['controller'])) {
                DBM::makeController($table['controller']);
            }

            try {
                if ($object = $this->addOrUpdateObject($table)) {
                    foreach ($columns as $column) {
                        $this->addOrUpdateField($column, $object);
                    }
                }

                return response()->json([
                    'success' => true,
                    'object' => $request->object,
                    'fields' => $request->fields,
                ]);
            } catch (\Exception $e) {
                return $this->generateError([$e->getMessage()]);
            }
        }

        return response()->json(['success' => false]);
    }

    /**
     * Create a new model if not exists.
     *
     * @param array $table
     *
     * @return \Illuminate\Http\JsonResponse|true
     */
    public function makeModel($table)
    {
        if (empty($table['model'])) {
            return $this->generateError(['Model Must be provided']);
        }

        if ($table['makeModel'] && ! class_exists($table['model'])) {
            DBM::makeModel($table['model'], $table['name']);
        }

        if (! $table['makeModel'] && ! class_exists($table['model'])) {
            $error = "Create model {$table['model']} first or checked create model option";

            return $this->generateError([$error]);
        }

        return true;
    }

    /**
     * Create|Update Object.
     *
     * @param array $table
     *
     * @return \CodexShaper\DBM\Models\DBM_Object|\CodexShaper\DBM\Models\DBM_MongoObject|false
     */
    public function addOrUpdateObject($table)
    {
        $object = DBM::Object()->where('name', $table['name'])->first();
        $action = 'update';
        if (! $object) {
            $object = DBM::Object();
            $object->name = $table['name'];
            $action = 'save';
        }
        $object->slug = Str::slug($table['slug']);
        $object->display_name = ucfirst($table['display_name']);
        $object->model = $table['model'];
        $object->controller = $table['controller'];
        $object->details = [
            'findColumn' => $table['findColumn'],
            'searchColumn' => $table['searchColumn'],
            'perPage' => $table['perPage'],
            'orderColumn' => $table['orderColumn'],
            'orderDisplayColumn' => $table['orderDisplayColumn'],
            'orderDirection' => $table['orderDirection'],
        ];

        if ($object->{$action}()) {
            return $object;
        }

        return false;
    }

    /**
     * Create|Update Object Field.
     *
     * @param array $column
     * @param \CodexShaper\DBM\Models\DBM_Object|\CodexShaper\DBM\Models\DBM_MongoObject $object
     *
     * @return \CodexShaper\DBM\Models\DBM_Field|\CodexShaper\DBM\Models\DBM_MongoField
     */
    public function addOrUpdateField($column, $object)
    {
        $field = DBM::Field()->where([
            'dbm_object_id' => $object->id,
            'name' => $column['name'],
        ])->first();

        $action = 'update';

        if (! $field) {
            $field = DBM::Field();
            $field->dbm_object_id = $object->id;
            $field->name = $column['name'];
            $action = 'save';
        }

        $field->display_name = ucfirst($column['display_name']);
        $field->type = $column['type'];
        $field->create = isset($column['create']) ? $column['create'] : false;
        $field->read = isset($column['read']) ? $column['read'] : false;
        $field->edit = isset($column['edit']) ? $column['edit'] : false;
        $field->delete = isset($column['delete']) ? $column['delete'] : false;
        $field->order = $column['order'];
        $field->function_name = isset($column['function_name']) ? $column['function_name'] : '';
        $field->settings = json_decode($column['settings']);

        $field->{$action}();
    }

    /**
     * Delete CRUD.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {
            if (($response = DBM::authorize('crud.delete')) !== true) {
                return $response;
            }

            $object = DBM::Object()->where('name', $request->table)->first();
            if ($object) {
                $object->fields()->delete();
                $object->delete();

                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false]);
    }

    /**
     * Generate an error.
     *
     * @param array $errors
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function generateError($errors)
    {
        return response()->json([
            'success' => false,
            'errors' => $errors,
        ], 400);
    }
}
