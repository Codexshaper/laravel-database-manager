<?php

namespace CodexShaper\DBM\Http\Controllers;

use CodexShaper\DBM\Database\Drivers\MongoDB\Type;
use CodexShaper\DBM\Database\Schema\Table;
use CodexShaper\DBM\Facades\Driver;
use DBM;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CrudController extends Controller
{

    public function index()
    {
        return view('dbm::app');
    }

    public function all(Request $request)
    {
        if ($request->ajax()) {

            if (($response = DBM::authorize('crud.browse')) !== true) {
                return $response;
            }

            $perPage = (int) $request->perPage;
            $query   = $request->q;

            $userPermissions = DBM::userPermissions();
            $tables          = Table::paginate($perPage, null, [], $query);
            $objects         = DBM::Object()->all();

            $newTables = [];

            foreach ($tables as $table) {
                foreach ($objects as $object) {
                    if ($table == $object->name) {
                        $newTables[] = [
                            'name'   => $table,
                            'isCrud' => true,
                        ];

                        continue 2;
                    }
                }

                $newTables[] = [
                    'name'   => $table,
                    'isCrud' => false,
                ];
            }

            return response()->json([
                'success'         => true,
                'tables'          => $newTables,
                'userPermissions' => $userPermissions,
                'pagination'      => $tables,
            ]);
        }

        return response()->json(['success' => false]);
    }

    public function getObjectDetails(Request $request)
    {
        if ($request->ajax()) {

            try
            {
                if (!Table::exists($request->table)) {

                    throw new \Exception("Sorry! There is no table", 1);

                }

                $tableName           = $request->table;
                $namespace           = DBM::getModelNamespace();
                $relationship_tables = Table::all();
                $isCrudExists        = false;

                if ($object = DBM::Object()->where('name', $tableName)->first()) {

                    if (($response = DBM::authorize('crud.update')) !== true) {
                        return $response;
                    }

                    $isCrudExists = true;

                    $model = $object->model;

                    if ($model == null) {
                        $namespace = DBM::getModelNamespace();

                        $modelName = ucfirst(Str::singular($object->name));

                        $model = $namespace . '\\' . $modelName;
                        // $object->model = $model;
                    }

                    $fields = $object->fields()->orderBy('order', 'ASC')->get();
                } else {
                    if (($response = DBM::authorize('crud.create')) !== true) {
                        return $response;
                    }
                    $table = Table::getTable($tableName);

                    $modelName = ucfirst(Str::singular($table['name']));
                    $model     = $namespace . '\\' . $modelName;

                    $object               = new \stdClass;
                    $object->name         = $table['name'];
                    $object->slug         = Str::slug($table['name']);
                    $object->display_name = ucfirst($table['name']);
                    $object->model        = $model;
                    $object->controller   = '';

                    $fields = [];
                    $order  = 1;

                    foreach ($table['columns'] as $column) {

                        $fields[] = (object) [
                            'name'          => $column->name,
                            'display_name'  => ucfirst($column->name),
                            'type'          => DatabaseController::getInputType($column->type['name']),
                            // 'required'      => ($column->autoincrement) ? true : false,
                            'create'        => ($column->autoincrement) ? false : true,
                            'read'          => ($column->autoincrement) ? false : true,
                            'edit'          => ($column->autoincrement) ? false : true,
                            'delete'        => ($column->autoincrement) ? false : true,
                            'function_name' => '',
                            'order'         => $order,
                            'settings'      => '{ }',
                        ];

                        $order++;
                    }
                }

                $object->makeModel = false;

                $relationshipDetails = (object) [
                    'type'                => 'hasOne',
                    'foreignTableDetails' => Table::getTable($relationship_tables[0]),
                    'localTableDetails'   => Table::getTable($tableName),

                ];

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'errors'  => [$e->getMessage()],
                ], 400);
            }

            return response()->json([
                'success'              => true,
                'relationship_tables'  => $relationship_tables,
                'relationship_details' => $relationshipDetails,
                'object'               => $object,
                'fields'               => $fields,
                'isCrudExists'         => $isCrudExists,
                'userPermissions'      => DBM::userPermissions(),
                'driver'               => Driver::getConnectionName(),
            ]);
        }

        return response()->json(['success' => false]);
    }

    public function storeOrUpdate(Request $request)
    {
        if ($request->ajax()) {

            if (($response = DBM::authorize('crud.browse')) !== true) {
                return $response;
            }

            $table   = $request->object;
            $columns = $request->fields;
            $action  = ($request->isCrudExists) ? 'edit' : 'add';

            // return response()->json(['columns' => $columns, 'action' => $action]);

            if ($action == 'add') {

                if (($response = DBM::authorize('crud.create')) !== true) {
                    return $response;
                }

                try
                {
                    if ($table['model'] == '' || $table['model'] == null) {
                        return response()->json([
                            'success' => false,
                            'errors'  => ["Model Must be provided"],
                        ], 400);

                    }

                    if ($table['makeModel'] == true && !class_exists($table['model'])) {
                        \DBM::makeModel($table['model'], $table['name']);
                    } else if ($table['makeModel'] == false && !class_exists($table['model'])) {
                        return response()->json([
                            'success' => false,
                            'errors'  => ["Create model '" . $table['model'] . "' first or checked create model option"],
                        ], 400);
                    }

                    if (!class_exists($table['controller'])) {
                        \DBM::makeController($table['controller']);
                    }

                    $object               = DBM::Object();
                    $object->name         = $table['name'];
                    $object->slug         = Str::slug($table['slug']);
                    $object->display_name = ucfirst($table['display_name']);
                    $object->model        = $table['model'];
                    $object->controller   = $table['controller'];
                    $object->details      = [
                        'findColumn'         => $table['findColumn'],
                        'searchColumn'       => $table['searchColumn'],
                        'perPage'            => $table['perPage'],
                        'orderColumn'        => $table['orderColumn'],
                        'orderDisplayColumn' => $table['orderDisplayColumn'],
                        'orderDirection'     => $table['orderDirection'],
                    ];

                    if ($object->save()) {

                        foreach ($columns as $column) {

                            $field                = DBM::Field();
                            $field->dbm_object_id = $object->id;
                            $field->name          = $column['name'];
                            $field->display_name  = ucfirst($column['display_name']);
                            $field->type          = $column['type'];
                            // $field->required      = isset($column['required']) ? $column['required'] : false;
                            $field->create        = isset($column['create']) ? $column['create'] : false;
                            $field->read          = isset($column['read']) ? $column['read'] : false;
                            $field->edit          = isset($column['edit']) ? $column['edit'] : false;
                            $field->delete        = isset($column['delete']) ? $column['delete'] : false;
                            $field->order         = $column['order'];
                            $field->function_name = $column['function_name'];
                            $field->settings      = json_decode($column['settings']);

                            $field->save();
                        }

                    }

                } catch (\Exception $e) {
                    return $this->generateError([$e->getMessage()]);
                }
            } else {

                if (($response = DBM::authorize('crud.update')) !== true) {
                    return $response;
                }

                try
                {

                    if ($table['model'] == '' || $table['model'] == null) {
                        return $this->generateError(["Model Must be provided"]);

                    }

                    if ($table['makeModel'] == true) {
                        \DBM::makeModel($table['model'], $table['name']);
                    } else if ($table['makeModel'] == false && !class_exists($table['model'])) {
                        return $this->generateError(["Create mode '" . $table['model'] . "' first."]);
                    }

                    if (!class_exists($table['controller'])) {
                        \DBM::makeController($table['controller']);
                    }

                    $object               = DBM::Object()->where('name', $table['name'])->first();
                    $object->slug         = Str::slug($table['slug']);
                    $object->display_name = ucfirst($table['display_name']);
                    $object->model        = $table['model'];
                    $object->controller   = $table['controller'];
                    $object->details      = [
                        'findColumn'         => $table['findColumn'],
                        'searchColumn'       => $table['searchColumn'],
                        'perPage'            => $table['perPage'],
                        'orderColumn'        => $table['orderColumn'],
                        'orderDisplayColumn' => $table['orderDisplayColumn'],
                        'orderDirection'     => $table['orderDirection'],
                    ];

                    if ($object->update()) {

                        foreach ($columns as $column) {

                            $field = DBM::Field()->where([
                                'dbm_object_id' => $object->id,
                                'name'          => $column['name'],
                            ])->first();

                            $field->display_name = ucfirst($column['display_name']);
                            $field->type         = $column['type'];
                            // $field->required      = isset($column['required']) ? $column['required'] : false;
                            $field->create        = isset($column['create']) ? $column['create'] : false;
                            $field->read          = isset($column['read']) ? $column['read'] : false;
                            $field->edit          = isset($column['edit']) ? $column['edit'] : false;
                            $field->delete        = isset($column['delete']) ? $column['delete'] : false;
                            $field->order         = $column['order'];
                            $field->function_name = isset($column['function_name']) ? $column['function_name'] : "";
                            $field->settings      = json_decode($column['settings']);

                            $field->update();
                        }
                    }

                } catch (\Exception $e) {
                    return $this->generateError([$e->getMessage()]);
                }
            }

            return response()->json([
                'success' => true,
                'object'  => $request->object,
                'fields'  => $request->fields,
            ]);
        }

        return response()->json(['success' => false]);
    }

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
    /*
     * RelationShip
     */
    public function getRelation(Request $request)
    {
        if ($request->ajax()) {

            if (($response = DBM::authorize('relationship.update')) !== true) {
                return $response;
            }

            $tableName = $request->table;
            $field     = json_decode($request->field);

            $object = DBM::Object()->where('name', $tableName)->first();
            $fields = $object->fields;

            $prefix = (Driver::isMongoDB()) ? "_" : "";

            foreach ($fields as $fld) {
                if ($fld->id == $field->{$prefix . "id"}) {

                    $relationship = $fld->settings;
                    $localTable   = $relationship['localTable'];
                    $foreignTable = $relationship['foreignTable'];
                    $pivotTable   = $relationship['pivotTable'];

                    $field->localFields   = Table::getTable($localTable);
                    $field->foreignFields = Table::getTable($foreignTable);
                    $field->pivotFields   = Table::getTable($pivotTable);
                    $field->relationship  = $relationship;
                }
            }

            return response()->json(['success' => true, 'field' => $field]);
        }

        return response()->json(['success' => false]);
    }

    public function addRelation(Request $request)
    {
        if ($request->ajax()) {

            if (($response = DBM::authorize('relationship.create')) !== true) {
                return $response;
            }

            $relationship = $request->relationship;

            // return response()->json(['success' => true, 'relationship' => $relationship['displayLevel']]);

            if (!class_exists($relationship['localModel'])) {

                $error = $relationship['localModel'] . " Model not found. Please create the " . $relationship['localModel'] . " model first";
                return $this->generateError([$error]);
            }

            if (!class_exists($relationship['foreignModel'])) {

                $error = $relationship['foreignModel'] . " Model not found. Please create the " . $relationship['foreignModel'] . " model first";
                return $this->generateError([$error]);
            }

            $fieldName = strtolower(Str::singular($relationship['localTable']) . '_' . $relationship['type'] . '_' . Str::singular($relationship['foreignTable']) . '_relationship');
            $settings  = [
                'relationType'    => $relationship['type'],
                'localModel'      => $relationship['localModel'],
                'localTable'      => $relationship['localTable'],
                'localKey'        => $relationship['localKey'],
                'foreignModel'    => $relationship['foreignModel'],
                'foreignTable'    => $relationship['foreignTable'],
                'foreignKey'      => $relationship['foreignKey'],
                'displayLabel'    => $relationship['displayLabel'],
                'pivotTable'      => $relationship['pivotTable'],
                'parentPivotKey'  => $relationship['parentPivotKey'],
                'relatedPivotKey' => $relationship['relatedPivotKey'],
            ];

            $object = DBM::Object()->where('name', $relationship['localTable'])->first();
            $order  = DBM::Field()->where('dbm_object_id', $object->id)->max('order');

            $field                = DBM::Field();
            $field->dbm_object_id = $object->id;
            $field->name          = $fieldName;
            $field->type          = 'relationship';
            $field->display_name  = ucfirst($relationship['foreignTable']);
            $field->order         = $order + 1;
            $field->settings      = $settings;
            if ($field->save()) {
                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false]);
    }

    public function updateRelation(Request $request)
    {
        if ($request->ajax()) {

            if (($response = DBM::authorize('relationship.update')) !== true) {
                return $response;
            }

            $relationship = $request->relationship;
            $field        = $request->field;

            // return response()->json(['relationship' => $relationship, 'field' => $field]);

            $settings = [
                'relationType'    => $relationship['type'],
                'localModel'      => $relationship['localModel'],
                'localTable'      => $relationship['localTable'],
                'localKey'        => $relationship['localKey'],
                'foreignModel'    => $relationship['foreignModel'],
                'foreignTable'    => $relationship['foreignTable'],
                'foreignKey'      => $relationship['foreignKey'],
                'displayLabel'    => $relationship['displayLabel'],
                'pivotTable'      => $relationship['pivotTable'],
                'parentPivotKey'  => $relationship['parentPivotKey'],
                'relatedPivotKey' => $relationship['relatedPivotKey'],
            ];

            $field           = DBM::Field()::find($field['id']);
            $field->settings = $settings;
            if ($field->update()) {
                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false]);
    }

    public function deleteRelation(Request $request)
    {
        if ($request->ajax()) {

            if (($response = DBM::authorize('relationship.delete')) !== true) {
                return $response;
            }

            $tableName = $request->table;
            $data      = json_decode($request->field);

            $field = DBM::Field()::find($data->id);

            if ($field->delete()) {
                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false]);
    }

    protected function generateError($errors)
    {
        return response()->json([
            'success' => false,
            'errors'  => $errors,
        ], 400);
    }
}
