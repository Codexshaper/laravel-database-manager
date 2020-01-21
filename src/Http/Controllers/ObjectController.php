<?php

namespace CodexShaper\DBM\Http\Controllers;

use CodexShaper\DBM\Database\Schema\Table;
use CodexShaper\DBM\Facades\Driver;
use CodexShaper\DBM\Facades\Manager as DBM;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ObjectController extends Controller
{
    /**
     * Get all objects.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all(Request $request)
    {
        if ($request->ajax()) {
            if (($response = DBM::authorize('crud.browse')) !== true) {
                return $response;
            }

            $perPage = (int) $request->perPage;
            $query = $request->q;

            $userPermissions = DBM::userPermissions();
            $tables = Table::paginate($perPage, null, [], $query);
            $newTables = $this->filterCrudTables($tables);

            return response()->json([
                'success' => true,
                'tables' => $newTables,
                'userPermissions' => $userPermissions,
                'pagination' => $tables,
            ]);
        }

        return response()->json(['success' => false]);
    }

    /**
     * Filter CRUD Tables.
     * Check CRUD exists or not.
     *
     * @param mixed $tables
     *
     * @return array
     */
    public function filterCrudTables($tables)
    {
        $objects = DBM::Object()->all();
        $newTables = [];

        foreach ($tables as $table) {
            foreach ($objects as $object) {
                if ($table == $object->name) {
                    $newTables[] = [
                        'name' => $table,
                        'isCrud' => true,
                    ];
                    continue 2;
                }
            }

            $newTables[] = [
                'name' => $table,
                'isCrud' => false,
            ];
        }

        return $newTables;
    }

    /**
     * Get Object details.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getObjectDetails(Request $request)
    {
        if ($request->ajax()) {
            try {
                if (! Table::exists($request->table)) {
                    throw new \Exception('Sorry! There is no table', 1);
                }

                $tableName = $request->table;
                $details = $this->getObject($tableName);
                $permission = $details['isCrudExists'] ? 'update' : 'create';

                if (($response = DBM::authorize("crud.{$permission}")) !== true) {
                    return $response;
                }

                $relationship = $this->getRelationshipDetails($tableName);

                return response()->json([
                    'success' => true,
                    'relationship_tables' => $relationship['tables'],
                    'relationship_details' => $relationship['details'],
                    'object' => $details['object'],
                    'fields' => $details['fields'],
                    'isCrudExists' => $details['isCrudExists'],
                    'userPermissions' => DBM::userPermissions(),
                    'driver' => Driver::getConnectionName(),
                ]);
            } catch (\Exception $e) {
                return $this->generateError([$e->getMessage()]);
            }
        }

        return response()->json(['success' => false]);
    }

    /**
     * Get Object.
     *
     * @param string $tableName
     *
     * @return array
     */
    public function getObject($tableName)
    {
        $isCrudExists = false;
        $fields = [];

        if ($object = DBM::Object()->where('name', $tableName)->first()) {
            $isCrudExists = true;
            if (! $object->model) {
                $object->model = DBM::generateModelName($object->name);
            }
            $fields = $object->fields()->orderBy('order', 'ASC')->get();
        }

        if (! $object) {
            $table = Table::getTable($tableName);

            $object = new \stdClass;
            $object->name = $table['name'];
            $object->slug = Str::slug($table['name']);
            $object->display_name = ucfirst($table['name']);
            $object->model = DBM::generateModelName($table['name']);
            $object->controller = '';

            $fields = $this->prepareFields($table);
        }

        $object->makeModel = false;

        return [
            'object' => $object,
            'fields' => $fields,
            'isCrudExists' => $isCrudExists,
        ];
    }

    /**
     * Prepare Object Fields.
     *
     * @param array $table
     *
     * @return array
     */
    public function prepareFields($table)
    {
        $fields = [];
        $order = 1;

        foreach ($table['columns'] as $column) {
            $fields[] = (object) [
                'name' => $column->name,
                'display_name' => ucfirst($column->name),
                'type' => DatabaseController::getInputType($column->type['name']),
                'create' => ($column->autoincrement) ? false : true,
                'read' => ($column->autoincrement) ? false : true,
                'edit' => ($column->autoincrement) ? false : true,
                'delete' => ($column->autoincrement) ? false : true,
                'function_name' => '',
                'order' => $order,
                'settings' => '{ }',
            ];

            $order++;
        }

        return $fields;
    }

    /**
     * Get Relationship details.
     *
     * @param string $tableName
     *
     * @return array
     */
    public function getRelationshipDetails($tableName)
    {
        $tables = Table::all();

        $relationshipDetails = (object) [
            'type' => 'hasOne',
            'foreignTableDetails' => Table::getTable($tables[0]),
            'localTableDetails' => Table::getTable($tableName),
        ];

        return [
            'tables' => $tables,
            'details' => $relationshipDetails,
        ];
    }

    /**
     * Get errors.
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
