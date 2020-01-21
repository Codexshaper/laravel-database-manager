<?php

namespace CodexShaper\DBM\Http\Controllers;

use CodexShaper\DBM\Database\Schema\Table;
use CodexShaper\DBM\Facades\Driver;
use CodexShaper\DBM\Facades\Manager as DBM;
use CodexShaper\DBM\Models\CollectionField;
use CodexShaper\DBM\Models\DBM_Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DatabaseController extends Controller
{
    /**
     * Load all database tables.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        return view('dbm::app');
    }

    /**
     * Create CRUD.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $table = json_decode($request->table, true);

            if (($response = DBM::authorize('database.create')) !== true) {
                return $response;
            }

            try {
                Table::create($request->table);

                if (Driver::isMongoDB()) {
                    $collection = new DBM_Collection;
                    $collection->name = $table['name'];
                    $collection->old_name = $table['name'];
                    $collection->extra = [];

                    if ($collection->save()) {
                        $id = $collection->_id;

                        $collection_field = new CollectionField;

                        $collection_field->dbm_collection_id = $id;
                        $collection_field->name = '_id';
                        $collection_field->old_name = '_id';
                        $collection_field->type = 'ObjectId';
                        $collection_field->index = 'PRIMARY_KEY';
                        $collection_field->extra = [];

                        $collection_field->save();
                    }
                }

                return response()->json(['success' => true]);
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
     * Update MongoDB Collection.
     *
     * @param array $table
     *
     * @return void
     */
    public function updateMongoDbTable($table)
    {
        $tableName = $table['oldName'];
        $newName = $table['name'];
        $columns = $table['columns'];

        if ($collection = DBM_Collection::where('name', $tableName)->first()) {
            if ($tableName != $newName) {
                $collection->name = $newName;
                $collection->old_name = $newName;
                $collection->update();
            }
        } else {
            $collection = new DBM_Collection;
            $collection->name = $newName;
            $collection->old_name = $newName;
            $collection->save();
        }

        if ($collection) {
            $id = $collection->_id;
            $fieldNames = (! empty($collection->fields)) ? $collection->fields->pluck('old_name')->toArray() : [];

            foreach ($columns as $column) {
                if (in_array($column['oldName'], $fieldNames)) {
                    $collection_field = CollectionField::where([
                        'dbm_collection_id' => $collection->_id,
                        'old_name' => $column['oldName'],
                    ])->first();

                    $collection_field->name = $column['name'];
                    $collection_field->old_name = $column['oldName'];
                    $collection_field->type = $column['type']['name'];
                    $collection_field->update();
                    $fieldNames = array_values(array_diff($fieldNames, [$column['oldName']]));
                } else {
                    $collection_field = new CollectionField;

                    $collection_field->dbm_collection_id = $id;
                    $collection_field->name = $column['name'];
                    $collection_field->old_name = $column['name'];
                    $collection_field->type = $column['type']['name'];
                    $collection_field->index = '';
                    $collection_field->extra = [];

                    $collection_field->save();
                }
            }

            if (count($fieldNames) > 0) {
                foreach ($fieldNames as $fieldName) {
                    $field = CollectionField::where([
                        'dbm_collection_id' => $id,
                        'name' => $fieldName, ])->first();
                    $field->delete();
                }
            }
        }
    }

    /**
     * Create|Update CRUD Field.
     *
     * @param \CodexShaper\DBM\Models\DBM_Object|\CodexShaper\DBM\Models\DBM_MongoObject $object
     * @param array $column
     * @param array $fieldNames
     *
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function addOrUpdateCrudField($object, $column, &$fieldNames)
    {
        $columnType = $column['type'];

        if (in_array($column['oldName'], $fieldNames)) {
            $field = DBM::Field()->where([
                'dbm_object_id' => $object->id,
                'name' => $column['oldName'],
            ])->first();

            $field->name = $column['name'];
            if ($column['oldName'] != $column['name']) {
                $field->display_name = ucfirst($column['name']);
            }
            $field->order = $column['order'];
            $field->update();

            $fieldNames = array_values(array_diff($fieldNames, [$column['oldName']]));
        } else {
            if (DBM::Field()->where([
                'dbm_object_id' => $object->id,
                'name' => $column['name'], ])->first()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['Field name must be unique. '.$column['name'].' are duplicate'],
                ], 400);
            }

            $field = DBM::Field();
            $field->dbm_object_id = $object->id;
            $field->name = $column['name'];
            $field->display_name = ucfirst($column['name']);
            $field->type = static::getInputType($columnType['name']);
            $field->order = $column['order'];

            if ($column['autoincrement'] == true) {
                $field->create = false;
                $field->read = false;
                $field->edit = false;
                $field->delete = false;
            }

            $field->save();
        }
    }

    /**
     * Update CRUD Fields.
     *
     * @param array $table
     *
     * @return void
     */
    public function updateCrudFields($table)
    {
        $tableName = $table['oldName'];
        $newName = $table['name'];
        $columns = $table['columns'];

        if ($tableName != $newName) {
            DBM::Object()->where('slug', Str::slug($tableName))->update([
                'name' => $newName,
                'slug' => Str::slug($newName),
                'display_name' => ucfirst($newName),
            ]);

            $tableName = $newName;
        }

        if ($object = DBM::Object()::where('slug', Str::slug($tableName))->first()) {
            $fieldNames = $object->fields->pluck('name')->toArray();
            // $relationshipItems = [];

            foreach ($columns as $column) {
                $this->addOrUpdateCrudField($object, $column, $fieldNames);
            }

            if (count($fieldNames) > 0) {
                foreach ($fieldNames as $fieldName) {
                    $field = DBM::Field()->where([
                        'dbm_object_id' => $object->id,
                        'name' => $fieldName, ])->first();
                    if ($field->type != 'relationship') {
                        $field->delete();
                    }
                }
            }
        }
    }

    /**
     * Update Table.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        if ($request->ajax()) {
            if (($response = DBM::authorize('database.update')) !== true) {
                return $response;
            }

            $table = $request->table;

            if (! is_array($table)) {
                $table = json_decode($table, true);
            }

            $tableName = $table['oldName'];

            try {
                // Update Template
                (new \CodexShaper\DBM\Http\Controllers\TemplateController)->updateTemplates($request);

                if (Table::exists($tableName)) {
                    if (Driver::isMongoDB()) {
                        $this->updateMongoDbTable($table);
                    }

                    // Update Database
                    Table::update($request->table);

                    // Update Crud fields
                    $this->updateCrudFields($table);

                    return response()->json(['success' => true]);
                }
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
     * Create Table.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {
            if (($response = DBM::authorize('database.delete')) !== true) {
                return $response;
            }

            $tableName = $request->table;

            try {
                if (Table::exists($tableName)) {
                    if (Driver::isMongoDB()) {
                        if ($collection = DBM_Collection::where('name', $tableName)->first()) {
                            $collection->fields()->delete();
                            $collection->delete();
                        }
                    }

                    if ($object = DBM::Object()->where('slug', Str::slug($tableName))->first()) {
                        $object->fields()->delete();
                        $object->delete();
                    }

                    Table::drop($tableName);

                    return response()->json(['success' => true]);
                }
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
     * Get input type.
     *
     * @param string $name
     *
     * @return string
     */
    public static function getInputType($name)
    {
        $types = static::inputTypes();
        foreach ($types as $type => $value) {
            if (in_array($name, $value)) {
                return $type;
            }
        }

        return 'text';
    }

    /**
     * Input Types.
     *
     * @return array
     */
    public static function inputTypes()
    {
        return [
            'number' => [
                'tinyint',
                'smallint',
                'mediumint',
                'integer',
                'int',
                'bigint',
                'decimal',
                'numeric',
            ],
            'text' => [
                'money',
                'float',
                'real',
                'double',
                'double precision',
                // String
                'char',
                'character',
                'varchar',
                'character varying',
                'string',
                'guid',
                'uuid',
                // Lists
                'json',
                'jsonb',
                'json_array',
            ],
            'password' => [
                'password',
            ],
            'email' => [],
            'select' => [],
            'radio' => [],
            'checkbox' => [
                'boolean',
            ],
            'textarea' => [
                'tinytext',
                'text',
                'mediumtext',
                'longtext',
                'tsquery',
                'tsvector',
                'xml',
            ],
            'date' => [
                'date',
                'datetime',
                'year',
                'timetz',
                'timestamptz',
                'datetimetz',
                'dateinterval',
                'interval',
            ],
            'timestamp' => [
                'timestamp',
            ],
            'color' => [],
            'file' => [],
            'image' => [],
            'hidden' => [],
            'month' => [],
            'week' => [],
            'time' => [
                'time',
            ],
            'url' => [],
            'tel' => [],
            'submit' => [],
            'search' => [],
        ];
    }
}
