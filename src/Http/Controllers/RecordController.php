<?php

namespace CodexShaper\DBM\Http\Controllers;

use CodexShaper\DBM\Database\Schema\Table;
use CodexShaper\DBM\Facades\Driver;
use CodexShaper\DBM\Facades\Manager as DBM;
use CodexShaper\DBM\Traits\RecordRelationship;
use CodexShaper\DBM\Traits\RecordTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class RecordController extends Controller
{
    use RecordTrait, RecordRelationship;

    /**
     * Get all permissions.
     *
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        return view('dbm::app');
    }

    /**
     * Create Record.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if ($request->ajax()) {
            if (($response = DBM::authorize('record.create')) !== true) {
                return $response;
            }

            $tableName = $request->table;
            $columns = json_decode($request->columns);
            $fields = json_decode($request->fields);

            $errors = $this->validation($fields, $columns);

            if (count($errors) > 0) {
                return $this->generateError($errors);
            }

            $object = DBM::Object()->where('name', $tableName)->first();

            if (is_string($object->model) && ! class_exists($object->model)) {
                return $this->generateError(['Model not found. Please create model first']);
            }

            try {
                $table = $this->processStoreData($request, $object);

                if ($table->save()) {
                    $this->storeRelationshipData($fields, $columns, $object, $table);

                    return response()->json(['success' => true]);
                }
            } catch (\Exception $e) {
                return $this->generateError([$e->getMessage()]);
            }
        }

        return response()->json(['success' => false]);
    }

    /**
     * Process data to store.
     *
     * @param \Illuminate\Http\Request $request
     * @param \CodexShaper\DBM\Models\DBM_Object|\CodexShaper\DBM\Models\DBM_MongoObject $object
     *
     * @return object
     */
    public function processStoreData($request, $object)
    {
        $originalColumns = Table::getColumnsName($request->table);
        $columns = json_decode($request->columns);
        $fields = json_decode($request->fields);
        $table = DBM::model($object->model, $request->table);

        foreach ($columns as $column => $value) {
            if (in_array($column, $originalColumns)) {
                if ($request->hasFile($column)) {
                    $value = $this->saveFiles($request, $column, $request->table);
                }

                if (! Driver::isMongoDB()) {
                    if ($functionName = $this->hasFunction($fields, $column)) {
                        $value = $this->executeFunction($functionName, $value);
                    }
                }

                $table->{$column} = $this->prepareStoreField($value, $request->table, $column);
            }
        }

        return $table;
    }

    /**
     * Update Record.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        if ($request->ajax()) {
            if (($response = DBM::authorize('record.update')) !== true) {
                return $response;
            }

            $tableName = $request->table;
            $columns = json_decode($request->columns);
            $fields = json_decode($request->fields);

            $errors = $this->validation($fields, $columns, 'update');

            if (count($errors) > 0) {
                return $this->generateError($errors);
            }

            $object = DBM::Object()->where('name', $tableName)->first();

            if (is_string($object->model) && ! class_exists($object->model)) {
                return $this->generateError(['Model not found. Please create model first']);
            }

            try {
                $table = $this->processUpdateData($request, $object);
                if ($table->update()) {
                    $this->updateRelationshipData($fields, $columns, $object, $table);

                    return response()->json(['success' => true]);
                }

                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return $this->generateError([$e->getMessage()]);
            }
        }

        return response()->json(['success' => false]);
    }

    /**
     * Process Data to update.
     *
     * @param \Illuminate\Http\Request $request
     * @param \CodexShaper\DBM\Models\DBM_Object|\CodexShaper\DBM\Models\DBM_MongoObject $object
     *
     * @return object
     */
    public function processUpdateData($request, $object)
    {
        $originalColumns = Table::getColumnsName($request->table);
        $columns = json_decode($request->columns);
        $fields = json_decode($request->fields);
        $key = $object->details['findColumn'];

        $table = DBM::model($object->model, $request->table)->where($key, $columns->{$key})->first();

        foreach ($columns as $column => $value) {
            if (in_array($column, $originalColumns)) {
                if ($request->hasFile($column)) {
                    $value = $this->saveFiles($request, $column, $request->table);
                }

                if ($value !== null && $value !== '') {
                    if (! Driver::isMongoDB()) {
                        if ($functionName = $this->hasFunction($fields, $column)) {
                            $value = $this->executeFunction($functionName, $value);
                        }
                    }

                    $table->{$column} = $this->prepareStoreField($value, $request->table, $column);
                }
            }
        }

        return $table;
    }

    /**
     * Delete Record.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        if ($request->ajax()) {
            if (($response = DBM::authorize('record.delete')) !== true) {
                return $response;
            }

            $tableName = $request->table;
            // $originalColumns = Table::getColumnsName($tableName);
            $columns = json_decode($request->columns);
            $fields = $request->fields;
            $object = DBM::Object()->where('name', $tableName)->first();
            $model = $object->model;
            $details = $object->details;
            $key = $details['findColumn'];

            if (is_string($model) && ! class_exists($model)) {
                return $this->generateError(['Model not found. Please create model first']);
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

    /**
     * Get Table Details.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTableDetails(Request $request)
    {
        if ($request->ajax()) {
            if (($response = DBM::authorize('record.browse')) !== true) {
                return $response;
            }

            $tableName = $request->table;
            $object = DBM::Object()->where('name', $tableName)->first();

            if (! $object) {
                return response()->json([
                    'success' => false,
                    'errors' => ['There is no Object details'],
                ], 400);
            }

            $createFields = $object->createFields();
            $browseFields = $object->readFields();
            $editFields = $object->editFields();
            $deleteFields = $object->deleteFields();
            $fields = $object->allFields();
            $foreignTableData = [];

            $createFields = $this->prepareRecordFields($createFields);
            $editFields = $this->prepareRecordFields($editFields);

            $model = $object->model;

            if (! class_exists($model)) {
                return $this->generateError(['Model not found. Please create model first']);
            }

            $perPage = (int) $request->perPage;
            $query = $request->q;
            $searchColumn = $object->details['searchColumn'];
            $records = DBM::model($model, $tableName)->paginate($perPage);

            if (! empty($query) && ! empty($searchColumn)) {
                $records = DBM::model($model, $tableName)
                    ->where($searchColumn, 'LIKE', '%'.$query.'%')
                    ->paginate($perPage);
            }

            $records = $this->prepareRelationshipData($records, $browseFields, $object);
            $recordsDetail = $this->prepareRecordDetails($records, $fields, $object, $request->findValue);

            $objectDetails = $object->details;
            $objectDetails['perPage'] = $perPage;

            return response()->json([
                'success' => true,
                'object' => $object,
                'objectDetails' => $objectDetails,
                'createFields' => $createFields,
                'browseFields' => $browseFields,
                'editFields' => $editFields,
                'deleteFields' => $deleteFields,
                'records' => $recordsDetail['records'],
                'record' => $recordsDetail['record'],
                'foreignTableData' => $foreignTableData,
                'userPermissions' => DBM::userPermissions(),
                'pagination' => $records,
                'isRecordModal' => Config::get('dbm.crud.record.is_modal'),
            ]);
        }

        return response()->json(['success' => false]);
    }
}
