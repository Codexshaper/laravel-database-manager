<?php

namespace CodexShaper\DBM\Traits;

use CodexShaper\DBM\Database\Drivers\MongoDB\Type;
use CodexShaper\DBM\Facades\Driver;
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

    /**
     * Store files in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $column
     * @param string $tableName
     *
     * @return array|string
     */
    public function saveFiles($request, $column, $tableName)
    {
        $files = $request->file($column);
        $values = [];
        foreach ($files as $file) {
            $fileName = Str::random(config('dbm.filesystem.random_length')).'.'.$file->getClientOriginalExtension();
            $path = trim(config('dbm.filesystem.dir'), '/').DIRECTORY_SEPARATOR.$tableName;
            $file->storeAs($path, $fileName);
            $values[] = $fileName;
        }

        if (count($values) > 1) {
            $value = $values;
            if (! Driver::isMongoDB()) {
                $value = json_encode($values);
            }
        } elseif (count($values) == 1) {
            $value = $values[0];
        }

        return $value;
    }

    /**
     * Prepare fields to store.
     *
     * @param array|string|int|float|bool $request
     * @param string $tableName
     * @param string $column
     *
     * @return array|string|int|float|bool
     */
    public function prepareStoreField($value, $tableName, $column)
    {
        $value = is_array($value) ? json_encode($value) : $value;

        if (Driver::isMongoDB()) {
            $fieldType = $this->getFieldType($tableName, $column);

            if (! in_array($fieldType, Type::getTypes())) {
                $this->generateError([$fieldType.' type not supported.']);
            }

            if ($fieldType != 'timestamp') {
                $value = Type::$fieldType($value);
            }
        }

        return $value;
    }

    /**
     * Prepare record fields.
     *
     * @param \Illuminate\Support\Collection $fields
     *
     * @return \Illuminate\Support\Collection
     */
    public function prepareRecordFields($fields)
    {
        foreach ($fields as $key => $field) {
            if ($field->type == 'relationship') {
                $relationship = $field->settings;
                $foreignModel = $relationship['foreignModel'];
                $foreignKey = $relationship['foreignKey'];
                $fields = $this->removeRelationshipKeyForBelongsTo($fields, $foreignKey);
                $field->foreignTableData = $foreignModel::all();
                $field->relationship = $relationship;
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

    /**
     * Remove field when belongs_to relation.
     *
     * @param \Illuminate\Support\Collection $fields
     * @param string $foreignKey
     *
     * @return array
     */
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

    /**
     * Prepare Record Details.
     *
     * @param mixed $records
     * @param \Illuminate\Support\Collection $fields
     * @param \CodexShaper\DBM\Models\DBM_Object|\CodexShaper\DBM\Models\DBM_MongoObject $object
     * @param string $findValue
     *
     * @return array
     */
    public function prepareRecordDetails($records, $fields, $object, $findValue)
    {
        $newRecords = [];
        $newRecord = new \stdClass();

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
            'records' => $newRecords,
            'record' => $newRecord,
        ];
    }

    /**
     * Get options for Dropdown, Selectbox, Radio button and other fields via controller.
     *
     * @param object $field
     *
     * @return array|mixed
     */
    public function getSettingOptions($field)
    {
        $options = $field->settings['options'];
        if (isset($options['controller'])) {
            $partials = explode('@', $options['controller']);
            $controllerName = $partials[0];
            $methodName = $partials[1];

            return app($controllerName)->{$methodName}();
        }
    }

    /**
     * Check validation and return errors if fails.
     *
     * @param array $fields
     * @param object $columns
     * @param string $action
     *
     * @return array
     */
    public function validation($fields, $columns, $action = 'create')
    {
        $errors = [];
        foreach ($fields as $field) {
            $name = $field->name;

            if (is_object($field->settings) && property_exists($field->settings, 'validation') !== false) {
                $validationSettings = $field->settings->validation;
                $rules = $this->prepareRules($columns, $action, $validationSettings);
                $data = [$name => $columns->{$name}];
                $validator = Validator::make($data, [$name => $rules]);
                if ($validator->fails()) {
                    foreach ($validator->errors()->all() as $error) {
                        $errors[] = $error;
                    }
                }
            }
        }

        return $errors;
    }

    /**
     * Prepare validation rules.
     *
     * @param object $columns
     * @param string $action
     * @param string|object $settings
     *
     * @return array|string
     */
    public function prepareRules($columns, $action, $settings)
    {
        $rules = '';

        if (is_string($settings)) {
            $rules = $settings;
        } elseif ($action == 'create' && isset($settings->create)) {
            $createSettings = $settings->create;
            $rules = $createSettings->rules;
        } elseif ($action == 'update' && isset($settings->update)) {
            $updateSettings = $settings->update;
            $rules = $updateSettings->rules;
            if (isset($updateSettings->localKey)) {
                $localKey = $updateSettings->localKey;
                $rules = $updateSettings->rules.','.$columns->{$localKey};
            }
        }

        return $rules;
    }

    /**
     * Get field name.
     *
     * @param string $collectionName
     * @param string $fieldName
     *
     * @return string
     */
    public function getFieldType($collectionName, $fieldName)
    {
        $collection = DBM_Collection::where('name', $collectionName)->first();

        return $collection->fields()->where('name', $fieldName)->first()->type;
    }

    /**
     * Generate errors and return response.
     *
     * @param array $errors
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateError($errors)
    {
        return response()->json([
            'success' => false,
            'errors' => $errors,
        ], 400);
    }

    /**
     * Check Function name is available for MySQL.
     *
     * @param array $fields
     * @param string $column
     *
     * @return string|false
     */
    public function hasFunction($fields, $column)
    {
        foreach ($fields as $field) {
            if ($field->name == $column && ($field->function_name != null || $field->function_name != '')) {
                return $field->function_name;
            }
        }

        return false;
    }

    /**
     * Execute MySQL Function.
     *
     * @param string $functionName
     * @param string|int|float|bool|null $column
     *
     * @return string|int|float|bool
     */
    public function executeFunction($functionName, $value = null)
    {
        $signature = ($value != null) ? "{$functionName}('{$value}')" : "{$functionName}()";
        $result = DB::raw("{$signature}");

        return $result;
    }
}
