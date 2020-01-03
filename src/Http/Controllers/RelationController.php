<?php

namespace CodexShaper\DBM\Http\Controllers;

use CodexShaper\DBM\Database\Drivers\MongoDB\Type;
use CodexShaper\DBM\Database\Schema\Table;
use CodexShaper\DBM\Facades\Driver;
use DBM;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RelationController extends Controller
{
    public function get(Request $request)
    {
        if ($request->ajax()) {

            if (($response = DBM::authorize('relationship.update')) !== true) {
                return $response;
            }

            $tableName = $request->table;
            $object    = DBM::Object()->where('name', $tableName)->first();
            $fields    = $object->fields;
            $field     = $this->prepareRelationshipField($fields, json_decode($request->field));

            return response()->json(['success' => true, 'field' => $field]);
        }

        return response()->json(['success' => false]);
    }

    public function prepareRelationshipField($fields, $field)
    {
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

        return $field;
    }

    public function add(Request $request)
    {
        if ($request->ajax()) {

            if (($response = DBM::authorize('relationship.create')) !== true) {
                return $response;
            }

            $relationship = $request->relationship;

            if (!class_exists($relationship['localModel'])) {

                $error = "{$relationship['localModel']} Model not found. Please create the {$relationship['localModel']} model first";
                return $this->generateError([$error]);
            }

            if (!class_exists($relationship['foreignModel'])) {

                $error = $relationship['foreignModel'] . " Model not found. Please create the " . $relationship['foreignModel'] . " model first";
                return $this->generateError([$error]);
            }

            $fieldName = $this->getFieldName($relationship);
            $settings  = $this->prepareSettings($relationship);

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

    public function getFieldName($relationship)
    {
        $localTable   = Str::singular($relationship['localTable']);
        $foreignTable = Str::singular($relationship['foreignTable']);
        $relationType = $relationship['type'];

        return strtolower("{$localTable}_{$relationType}_{$foreignTable}_relationship");
    }

    public function prepareSettings($relationship)
    {
        return [
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
    }

    public function update(Request $request)
    {
        if ($request->ajax()) {

            if (($response = DBM::authorize('relationship.update')) !== true) {
                return $response;
            }

            $relationship = $request->relationship;
            $field        = $request->field;

            $settings = $this->prepareSettings($relationship);

            $field           = DBM::Field()::find($field['id']);
            $field->settings = $settings;
            if ($field->update()) {
                return response()->json(['success' => true]);
            }
        }

        return response()->json(['success' => false]);
    }

    public function delete(Request $request)
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
