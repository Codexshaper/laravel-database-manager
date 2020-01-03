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
