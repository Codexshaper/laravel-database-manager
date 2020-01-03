<?php

namespace CodexShaper\DBM\Http\Controllers;

use CodexShaper\DBM\Database\Drivers\MongoDB\Type;
use CodexShaper\DBM\Database\Schema\Table;
use CodexShaper\DBM\Facades\Driver;
use CodexShaper\DBM\Traits\RecordHelper;
use DBM;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RecordController extends Controller
{
    use RecordHelper;

    public function index()
    {
        return view('dbm::app');
    }

    public function store(Request $request)
    {
        if ($request->ajax()) {

            if (($response = DBM::authorize('record.create')) !== true) {
                return $response;
            }

            $tableName       = $request->table;
            $originalColumns = Table::getColumnsName($tableName);
            $columns         = json_decode($request->columns);
            $fields          = json_decode($request->fields);

            // return response()->json(['columns' => $columns]);

            $errors = $this->validation($fields, $columns);

            if (count($errors) > 0) {
                return $this->generateError($errors);
            }

            $object = DBM::Object()->where('name', $tableName)->first();
            $model  = $object->model;

            if (!class_exists($model)) {
                return $this->generateError(["Model not found. Please create model first"]);
            }

            try {

                $table = DBM::model($model, $tableName);

                foreach ($columns as $column => $value) {
                    // $column = $field->name;
                    // $value  = $request->{$column};
                    if (in_array($column, $originalColumns)) {

                        if ($request->hasFile($column)) {
                            $files  = $request->file($column);
                            $values = [];
                            foreach ($files as $file) {
                                $fileName = Str::random(config('dbm.filesystem.random_length')) . '.' . $file->getClientOriginalExtension();
                                $path     = 'public/dbm/' . $tableName;
                                $file->storeAs($path, $fileName);
                                $values[] = $fileName;
                            }

                            // $value = json_encode($values);

                            if (count($values) > 1) {
                                $value = $values;
                                if (!Driver::isMongoDB()) {
                                    $value = is_array($values) ? json_encode($values) : $values;
                                }
                            } else if (count($values) == 1) {
                                $value = $values[0];
                            }
                        }

                        if (!Driver::isMongoDB()) {
                            if ($functionName = $this->hasFunction($fields, $column)) {
                                $value = $this->executeFunction($functionName, $value);
                            }
                        }

                        // if ($value != "" || !empty($value)) {

                        $table->{$column} = is_array($value) ? json_encode($value) : $value;

                        if (Driver::isMongoDB()) {

                            $fieldType = $this->getFieldType($tableName, $column);

                            if (!in_array($fieldType, Type::getTypes())) {
                                $this->generateError([$fieldType . " type not supported."]);
                            }

                            $table->{$column} = Type::$fieldType($value);

                        }
                        // }

                    }
                }

                if ($table->save()) {

                    foreach ($fields as $field) {

                        if (isset($field->relationship) && $field->relationship->relationType == "belongsToMany") {

                            $relationship = $field->relationship;

                            $localModel      = $relationship->localModel;
                            $localTable      = $relationship->localTable;
                            $foreignModel    = $relationship->foreignModel;
                            $pivotTable      = $relationship->pivotTable;
                            $parentPivotKey  = $relationship->parentPivotKey;
                            $relatedPivotKey = $relationship->relatedPivotKey;

                            $findColumn = $object->details['findColumn'];

                            $localObject = DBM::model($localModel, $localTable)::where($findColumn, $table->{$findColumn})->first();

                            DBM::Object()
                                ->setManyToManyRelation(
                                    $localObject,
                                    $foreignModel,
                                    $pivotTable,
                                    $parentPivotKey,
                                    $relatedPivotKey
                                )
                                ->belongs_to_many()
                                ->attach($columns->{$relatedPivotKey});

                        }

                        // else if (isset($field['relationship']) && $field['relationship']['relationType'] == "hasMany") {

                        //     $relationship = $field['relationship'];

                        //     $localModel   = $relationship['localModel'];
                        //     $findColumn   = $object->details['findColumn'];
                        //     $foreignModel = $relationship['foreignModel'];
                        //     $foreignKey   = $relationship['foreignKey'];
                        //     $localKey     = $relationship['localKey'];

                        //     $localObject = $localModel::where($findColumn, $table->{$findColumn})->first();

                        //     $items = [];

                        //     foreach ($columns[$foreignKey] as $key => $value) {
                        //         // return response()->json(['columns' => $columns['menu_id'], 'fields' => $value, 'model' => $foreignModel]);
                        //         $originalColumns    = Table::getColumnsName($relationship['foreignTable']);
                        //         $foreignTableFields = DBM::Object()->where('name', $relationship['foreignTable'])->first()->fields()->where('create', 1)->get();

                        //         $oldItem = $foreignModel::find($value);
                        //         $item    = new $foreignModel;

                        //         foreach ($foreignTableFields as $foreignTableField) {
                        //             if (in_array($foreignTableField->name, $originalColumns)) {
                        //                 if ($foreignTableField->name != $foreignKey) {
                        //                     $item->{$foreignTableField->name} = $oldItem->{$foreignTableField->name};
                        //                 }
                        //             }
                        //         }
                        //         DBM::Object()
                        //             ->setCommonRelation(
                        //                 $localObject,
                        //                 $foreignModel,
                        //                 $foreignKey,
                        //                 $localKey)
                        //             ->has_many()
                        //             ->save($item);
                        //     }

                        // }
                    }
                    return response()->json(['success' => true, 'object' => $object, 'table' => $table]);
                }

            } catch (\Exception $e) {
                return $this->generateError([$e->getMessage()]);
            }
        }

        return response()->json(['success' => false]);
    }

    public function update(Request $request)
    {
        if ($request->ajax()) {

            if (($response = DBM::authorize('record.update')) !== true) {
                return $response;
            }

            $tableName       = $request->table;
            $originalColumns = Table::getColumnsName($tableName);
            $columns         = json_decode($request->columns);
            $fields          = json_decode($request->fields);

            // return response()->json(['columns' => $columns->multiple_dropdown]);

            $errors = $this->validation($fields, $columns, 'update');

            if (count($errors) > 0) {
                return $this->generateError($errors);
            }

            $object  = DBM::Object()->where('name', $tableName)->first();
            $model   = $object->model;
            $details = $object->details;
            $key     = $details['findColumn'];

            if (!class_exists($model)) {
                return $this->generateError(["Model not found. Please create model first"]);
            }

            try {

                $table = DBM::model($model, $tableName)->where($key, $columns->{$key})->first();

                // return response()->json(['details' => $table, 'key' => $key]);

                foreach ($columns as $column => $value) {
                    // $column = $field->name;
                    // $value = $request->{$column};

                    if (in_array($column, $originalColumns)) {

                        if ($request->hasFile($column)) {
                            $files  = $request->file($column);
                            $values = [];
                            foreach ($files as $file) {
                                $fileName = Str::random(config('dbm.filesystem.random_length')) . '.' . $file->getClientOriginalExtension();
                                $path     = 'public/dbm/' . $tableName;
                                $file->storeAs($path, $fileName);
                                $values[] = $fileName;
                            }

                            // $value = json_encode($values);

                            if (count($values) > 1) {
                                $value = $values;
                                if (!Driver::isMongoDB()) {
                                    $value = is_array($values) ? json_encode($values) : $value;
                                }
                            } else if (count($values) == 1) {
                                $value = $values[0];
                            }
                        }

                        if ($value !== null && $value !== "") {

                            if (!Driver::isMongoDB()) {
                                if ($functionName = $this->hasFunction($fields, $column)) {

                                    $value = $this->executeFunction($functionName, $value);
                                }
                            }

                            $table->{$column} = is_array($value) ? json_encode($value) : $value;

                            if (Driver::isMongoDB()) {

                                $fieldType = $this->getFieldType($tableName, $column);

                                if (!in_array($fieldType, Type::getTypes())) {
                                    $this->generateError([$fieldType . " type not supported."]);
                                }

                                $table->{$column} = Type::$fieldType($value);

                            }
                        }
                    }
                }

                if ($table->update()) {

                    foreach ($fields as $field) {

                        // return response()->json(['fields' => $fields]);

                        if (isset($field->relationship)) {

                            $relationship = $field->relationship;

                            $localModel   = $relationship->localModel;
                            $localTable   = $relationship->localTable;
                            $foreignModel = $relationship->foreignModel;

                            if ($field->relationship->relationType == "belongsToMany") {
                                $pivotTable      = $relationship->pivotTable;
                                $parentPivotKey  = $relationship->parentPivotKey;
                                $relatedPivotKey = $relationship->relatedPivotKey;

                                $findColumn = $object->details['findColumn'];

                                $localObject = DBM::model($localModel, $localTable)->where($findColumn, $table->{$findColumn})->first();

                                DBM::Object()
                                    ->setManyToManyRelation(
                                        $localObject,
                                        $foreignModel,
                                        $pivotTable,
                                        $parentPivotKey,
                                        $relatedPivotKey
                                    )
                                    ->belongs_to_many()
                                    ->sync($columns->{$relatedPivotKey});
                            }

                        }
                    }

                    return response()->json(['success' => true]);
                }

            } catch (\Exception $e) {
                return $this->generateError([$e->getMessage()]);
            }
        }

        return response()->json(['success' => false]);
    }

    public function delete(Request $request)
    {
        if ($request->ajax()) {

            if (($response = DBM::authorize('record.delete')) !== true) {
                return $response;
            }

            $tableName = $request->table;
            // $originalColumns = Table::getColumnsName($tableName);
            $columns = json_decode($request->columns);
            $fields  = $request->fields;
            $object  = DBM::Object()->where('name', $tableName)->first();
            $model   = $object->model;
            $details = $object->details;
            $key     = $details['findColumn'];

            if (!class_exists($model)) {
                return $this->generateError(["Model not found. Please create model first"]);
            }

            try {
                $table = DBM::model($model, $tableName)->where($key, $columns->{$key})->first();
                if ($table) {
                    // Remove Relationship data
                    foreach ($fields as $field) {
                        $field = json_decode($field);
                        $this->removeRelationshipData($field, $object, $table);
                    }
                    // Check Table deleted successfully
                    if ($table->delete()) {
                        return response()->json(['success' => true]);
                    }
                }

            } catch (\Exception $e) {
                return $this->generateError([$e->getMessage()]);
            }
        }

        return response()->json(['success' => false]);
    }

    public function getTableDetails(Request $request)
    {
        if ($request->ajax()) {

            if (($response = DBM::authorize('record.browse')) !== true) {
                return $response;
            }

            $tableName = $request->table;
            $object    = DBM::Object()->where('name', $tableName)->first();

            if (!$object) {
                return response()->json([
                    'success' => false,
                    'errors'  => ["There is no Object details"],
                ], 400);
            }

            $createFields     = $object->createFields();
            $browseFields     = $object->readFields();
            $editFields       = $object->editFields();
            $deleteFields     = $object->deleteFields();
            $fields           = $object->allFields();
            $foreignTableData = [];

            // return response()->json(['createFields' => auth()->user()]);

            foreach ($createFields as $key => $field) {

                if ($field->type == 'relationship') {

                    $relationship = $field->settings;
                    // $relationshipType = $relationship['relationType'];
                    // $localModel       = $relationship['localModel'];
                    // $localKey         = $relationship['localKey'];
                    $foreignModel = $relationship['foreignModel'];
                    $foreignKey   = $relationship['foreignKey'];
                    // $relationshipType = $relationship['relationType'];

                    // if ($relationshipType == 'belongsTo') {
                    $createFields            = $this->removeRelationshipKeyForBelongsTo($createFields, $foreignKey);
                    $field->foreignTableData = $foreignModel::all();
                    $field->relationship     = $relationship;
                    // } else {
                    //     unset($fields[$key]);
                    // }
                } else {
                    if (isset($field->settings['options'])) {
                        $options = $this->getSettingOptions($field);
                        if (is_array($options)) {
                            $createFields[$key]->options = $options;
                        }
                    }

                }
            }

            foreach ($editFields as $key => $field) {

                if ($field->type == 'relationship') {

                    $relationship = $field->settings;
                    // $relationshipType = $relationship['relationType'];
                    // $localModel       = $relationship['localModel'];
                    // $localKey         = $relationship['localKey'];
                    $foreignModel = $relationship['foreignModel'];
                    $foreignKey   = $relationship['foreignKey'];
                    // $relationshipType = $relationship['relationType'];

                    // if ($relationshipType == 'belongsTo') {
                    $editFields              = $this->removeRelationshipKeyForBelongsTo($editFields, $foreignKey);
                    $field->foreignTableData = $foreignModel::all();
                    $field->relationship     = $relationship;
                    // } else {
                    //     unset($fields[$key]);
                    // }
                    continue;
                }

                if (isset($field->settings['options'])) {
                    $options = $this->getSettingOptions($field);
                    if (is_array($options)) {
                        $editFields[$key]->options = $options;
                    }
                }
            }
            $model = $object->model;

            if (!class_exists($model)) {
                return $this->generateError(["Model not found. Please create model first"]);
            }

            $perPage = (int) $request->perPage;
            $query   = $request->q;

            $searchColumn = $object->details['searchColumn'];

            $records = DBM::model($model, $tableName)->paginate($perPage);

            // return response()->json(['success' => $perPage]);

            if (!empty($query) && !empty($searchColumn)) {
                $records = DBM::model($model, $tableName)
                    ->where($searchColumn, 'LIKE', '%' . $query . '%')
                    ->paginate($perPage);
            }

            foreach ($records as $item => $record) {

                foreach ($browseFields as $field) {

                    if ($field->type == 'relationship') {

                        $relationship = $field->settings;

                        $findColumn = $object->details['findColumn'];

                        $localModel       = $relationship['localModel'];
                        $localKey         = $relationship['localKey'];
                        $foreignModel     = $relationship['foreignModel'];
                        $foreignKey       = $relationship['foreignKey'];
                        $relationshipType = $relationship['relationType'];
                        $displayLabel     = $relationship['displayLabel'];

                        if ($relationshipType == 'belongsTo') {

                            $localObject = $localModel::where($findColumn, $record->{$findColumn})->first();

                            $datas = DBM::Object()->setCommonRelation($localObject, $foreignModel, $foreignKey, $localKey)->belongs_to;

                            $record->{$field->name}  = $datas;
                            $field->displayLabel     = $displayLabel;
                            $field->localKey         = $localKey;
                            $field->foreignKey       = $foreignKey;
                            $field->relationshipType = $relationshipType;

                        } else if ($relationshipType == 'hasMany') {

                            $localObject = $localModel::where($findColumn, $record->{$findColumn})->first();
                            $datas       = DBM::Object()->setCommonRelation($localObject, $foreignModel, $foreignKey, $localKey)->has_many;

                            $record->{$field->name}  = $datas;
                            $field->displayLabel     = $displayLabel;
                            $field->localKey         = $localKey;
                            $field->foreignKey       = $foreignKey;
                            $field->relationshipType = $relationshipType;

                        } else if ($relationshipType == 'belongsToMany') {

                            $pivotTable      = $relationship['pivotTable'];
                            $parentPivotKey  = $relationship['parentPivotKey'];
                            $relatedPivotKey = $relationship['relatedPivotKey'];

                            $localObject = $localModel::where($findColumn, $record->{$findColumn})->first();

                            $datas = DBM::Object()->setManyToManyRelation($localObject, $foreignModel, $pivotTable, $parentPivotKey, $relatedPivotKey)->belongs_to_many;

                            $record->{$field->name}  = $datas;
                            $field->displayLabel     = $displayLabel;
                            $field->localKey         = $localKey;
                            $field->foreignKey       = $foreignKey;
                            $field->relationshipType = $relationshipType;
                        }
                    }
                }
            }

            $newRecords = [];
            $newRecord  = new \stdClass;

            foreach ($records as $item => $record) {

                foreach ($fields as $key => &$field) {

                    if (isset($record->{$field->name})) {

                        $record->{$field->name} = is_json($record->{$field->name}) ? json_decode($record->{$field->name}, true) : $record->{$field->name};
                    }
                }

                if ($request->findValue && $record->{$object->details['findColumn']} == $request->findValue) {
                    $newRecord = $record;
                }

                $newRecords[] = $record;
            }

            $objectDetails            = $object->details;
            $objectDetails['perPage'] = $perPage;

            return response()->json([
                'success'          => true,
                'object'           => $object,
                'objectDetails'    => $objectDetails,
                'createFields'     => $createFields,
                'browseFields'     => $browseFields,
                'editFields'       => $editFields,
                'deleteFields'     => $deleteFields,
                'records'          => $newRecords,
                'record'           => $newRecord,
                'foreignTableData' => $foreignTableData,
                'userPermissions'  => DBM::userPermissions(),
                'pagination'       => $records,
            ]);
        }

        return response()->json(['success' => false]);
    }
}
