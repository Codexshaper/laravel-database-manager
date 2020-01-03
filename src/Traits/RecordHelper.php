<?php

namespace CodexShaper\DBM\Traits;

use CodexShaper\DBM\Facades\Manager as DBM;
use CodexShaper\DBM\Models\DBM_Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

trait RecordHelper
{
    protected $name;
    protected $function_name;
    protected $localModel;
    protected $create;
    protected $relatedPivotKey;
    protected $parentPivotKey;
    protected $relationType;
    protected $details;
    protected $rules;
    protected $update;
    protected $type;
    protected $settings;
    protected $validation;
    protected $pivotTable;
    protected $foreignModel;

    public function saveFiles($request, $column, $tableName)
    {
        $files  = $request->file($column);
        $values = [];
        foreach ($files as $file) {
            $fileName = Str::random(config('dbm.filesystem.random_length')) . '.' . $file->getClientOriginalExtension();
            $path     = 'public/dbm/' . $tableName;
            $file->storeAs($path, $fileName);
            $values[] = $fileName;
        }

        if (count($values) > 1) {
            $value = $values;
            if (!Driver::isMongoDB()) {
                $value = json_encode($values);
            }
        } else if (count($values) == 1) {
            $value = $values[0];
        }

        return $value;
    }

    public function prepareStoreField($value, $tableName, $column)
    {
        $value = is_array($value) ? json_encode($value) : $value;

        if (Driver::isMongoDB()) {

            $fieldType = $this->getFieldType($tableName, $column);

            if (!in_array($fieldType, Type::getTypes())) {
                $this->generateError([$fieldType . " type not supported."]);
            }

            $value = Type::$fieldType($value);

        }

        return $value;
    }

    public function storeRelationshipData($fields, $columns, $object, $table)
    {
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
        }
    }

    public function updateRelationshipData($fields, $columns, $object, $table)
    {
        foreach ($fields as $field) {

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
    }

    public function removeRelationshipData($field, $object, $table)
    {
        if ($field->type == 'relationship') {

            $relationship = $field->settings;

            $localModel   = $relationship->localModel;
            $foreignModel = $relationship->foreignModel;

            $findColumn = $object->details['findColumn'];

            $localObject = $localModel::where($findColumn, $table->{$findColumn})->first();

            if ($relationship->relationType == 'belongsToMany') {

                $pivotTable      = $relationship->pivotTable;
                $parentPivotKey  = $relationship->parentPivotKey;
                $relatedPivotKey = $relationship->relatedPivotKey;

                DBM::Object()
                    ->setManyToManyRelation(
                        $localObject,
                        $foreignModel,
                        $pivotTable,
                        $parentPivotKey,
                        $relatedPivotKey
                    )
                    ->belongs_to_many()
                    ->detach();
            } else if ($relationship->relationType == 'hasMany') {

                $foreignKey = $relationship->foreignKey;
                $localKey   = $relationship->localKey;

                DBM::Object()
                    ->setCommonRelation(
                        $localObject,
                        $foreignModel,
                        $foreignKey,
                        $localKey)
                    ->has_many()
                    ->delete();
            }

        }
    }

    public function getSettingOptions($field)
    {
        $options = $field->settings['options'];
        if (isset($options['controller'])) {
            $partials       = explode('@', $options['controller']);
            $controllerName = $partials[0];
            $methodName     = $partials[1];

            return app($controllerName)->{$methodName}();
        }
    }

    public function removeRelationshipKeyForBelongsTo($fields, $foreignKey)
    {
        $results = [];

        foreach ($fields as $key => $field) {
            if ($field->name == $foreignKey) {
                unset($fields[$key]);
                continue;
            }
            $results[] = $field;
        }

        return $results;
    }

    public function validation($fields, $columns, $action = "create")
    {
        $errors = [];
        foreach ($fields as $field) {
            $name = $field->name;

            if (is_object($field->settings) && property_exists($field->settings, 'validation') !== false) {

                $validationSettings = $field->settings->validation;
                $rules              = $this->prepareRules($columns, $action, $validationSettings);
                $data               = [$name => $columns->{$name}];
                $validator          = Validator::make($data, [$name => $rules]);
                if ($validator->fails()) {
                    foreach ($validator->errors()->all() as $error) {
                        $errors[] = $error;
                    }
                }
            }
        }

        return $errors;
    }

    public function prepareRules($columns, $action, $settings)
    {
        $rules = '';

        if (is_string($settings)) {
            $rules = $settings;
        } else if ($action == 'create' && isset($settings->create)) {
            $createSettings = $settings->create;
            $rules          = $createSettings->rules;
        } else if ($action == 'update' && isset($settings->update)) {
            $updateSettings = $settings->update;
            $localKey       = $updateSettings->localKey;
            $rules          = $updateSettings->rules . ',' . $columns->{$localKey};
        }

        return $rules;
    }

    public function getFieldType($collectionName, $fieldName)
    {
        $collection = DBM_Collection::where('name', $collectionName)->first();

        return $collection->fields()->where('name', $fieldName)->first()->type;
    }

    public function generateError($errors)
    {
        return response()->json([
            'success' => false,
            'errors'  => $errors,
        ], 400);
    }

    public function hasFunction($fields, $column)
    {
        foreach ($fields as $field) {
            if ($field->name == $column && ($field->function_name != null || $field->function_name != "")) {
                return $field->function_name;
            }
        }

        return false;
    }

    public function executeFunction($functionName, $value = null)
    {
        $signature = ($value != null) ? "{$functionName}('{$value}')" : "{$functionName}()";

        $result = DB::raw("{$signature}");

        return $result;
    }
}
