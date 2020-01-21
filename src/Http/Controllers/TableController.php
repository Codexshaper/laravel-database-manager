<?php

namespace CodexShaper\DBM\Http\Controllers;

use CodexShaper\DBM\Database\Schema\Table;
use CodexShaper\DBM\Facades\Manager as DBM;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Get all Tables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all(Request $request)
    {
        if ($request->ajax()) {
            if (($response = DBM::authorize('database.browse')) !== true) {
                return $response;
            }

            try {
                $perPage = (int) $request->perPage;
                $query = $request->q;
                $tables = Table::paginate($perPage, null, [], $query);
                $userPermissions = DBM::userPermissions();

                $newTables = [];

                foreach ($tables as $table) {
                    $newTables[] = $table;
                }

                return response()->json([
                    'success' => true,
                    'tables' => $newTables,
                    'pagination' => $tables,
                    'userPermissions' => $userPermissions,
                    'coreTables' => config('dbm.core.tables', []),
                    'collation' => config('dbm.collation', 'utf8mb4_unicode_ci'),
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'errors' => [$e->getMessage()],
                ], 400);
            }
        }

        return response()->json(['success' => false]);
    }

    /**
     * Get Table Details.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTable(Request $request)
    {
        if ($request->ajax()) {
            if (($response = DBM::authorize('database.update')) !== true) {
                return $response;
            }

            $userPermissions = DBM::userPermissions();
            $table = Table::getTable($request->name);
            $object = DBM::Object()->where('name', $request->name)->first();
            $isCrudExists = false;
            $columns = $table['columns'];
            $newColumns = [];

            if ($object) {
                $fields = $object->fields()->orderBy('order', 'ASC')->get();
                if (count($fields) > 0) {
                    foreach ($fields as $field) {
                        foreach ($columns as $key => $column) {
                            if ($field->name == $column->name) {
                                // $column->id    = $field->id;
                                $column->order = $field->order;
                                $newColumns[] = $column;
                                unset($columns[$key]);
                                $columns = array_values($columns);
                            }
                        }
                    }

                    $table['columns'] = $newColumns;
                    // $columns          = $newColumns;
                    $isCrudExists = true;
                }
            }

            return response()->json([
                'success' => true,
                'table' => $table,
                'isCrudExists' => $isCrudExists,
                'userPermissions' => $userPermissions,
                'templates' => DBM::templates(),
            ]);
        }

        return response()->json(['success' => false]);
    }

    /**
     * Get Table Columns.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTableColumns(Request $request)
    {
        if ($request->ajax()) {
            if (($response = DBM::authorize('crud.update')) !== true) {
                return $response;
            }

            $table = Table::getTable($request->table);
            $fields = $table['columns'];

            return response()->json(['success' => true, 'fields' => $fields]);
        }

        return response()->json(['success' => false]);
    }
}
