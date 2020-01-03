<?php

namespace CodexShaper\DBM\Traits;

use CodexShaper\DBM\Database\Drivers\MongoDB\Type;
use CodexShaper\DBM\Facades\Driver;
use CodexShaper\DBM\Facades\Manager as DBM;
use CodexShaper\DBM\Models\DBM_Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

trait RecordTrait
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
    protected $localTable;

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

    public function prepareRecordFields($fields)
    {
        foreach ($fields as $key => $field) {

            if ($field->type == 'relationship') {

                $relationship            = $field->settings;
                $foreignModel            = $relationship['foreignModel'];
                $foreignKey              = $relationship['foreignKey'];
                $fields                  = $this->removeRelationshipKeyForBelongsTo($fields, $foreignKey);
                $field->foreignTableData = $foreignModel::all();
                $field->relationship     = $relationship;
                continue;
            }

            if (isset($field->settings['options'])) {
                $options = $this->getSettingOptions($field);
                if (is_array($options)) {
                    $fields[$key]->options = $options;
                }
            }
        }

        return $fields;
    }

    public function prepareJsonFieldData($records, $fields, $object, $findValue)
    {
        $newRecords = [];
        $newRecord  = new \stdClass();

        foreach ($records as $item => $record) {

            foreach ($fields as $key => &$field) {

                if (isset($record->{$field->name})) {

                    $record->{$field->name} = is_json($record->{$field->name}) ? json_decode($record->{$field->name}, true) : $record->{$field->name};
                }
            }

            if ($findValue && $record->{$object->details['findColumn']} == $findValue) {
                $newRecord = $record;
            }

            $newRecords[] = $record;
        }

        return [
            "records" => $newRecords,
            "record"  => $newRecord,
        ];
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
