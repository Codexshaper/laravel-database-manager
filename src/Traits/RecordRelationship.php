<?php

namespace CodexShaper\DBM\Traits;

use CodexShaper\DBM\Facades\Manager as DBM;

trait RecordRelationship
{
    protected $has_one;
    protected $has_many;
    protected $belongs_to_many;
    protected $belongs_to;

    /**
     * Prepare Relationship data to view.
     *
     * @param mixed $records
     * @param \Illuminate\Support\Collection $browseFields
     * @param \CodexShaper\DBM\Models\DBM_Object|\CodexShaper\DBM\Models\DBM_MongoObject $object
     *
     * @return mixed
     */
    public function prepareRelationshipData($records, $browseFields, $object)
    {
        foreach ($records as $item => $record) {
            foreach ($browseFields as $field) {
                if ($field->type == 'relationship') {
                    $relationship = $field->settings;

                    $findColumn = $object->details['findColumn'];

                    $localModel = $relationship['localModel'];
                    $localKey = $relationship['localKey'];
                    $foreignModel = $relationship['foreignModel'];
                    $foreignKey = $relationship['foreignKey'];
                    $relationshipType = $relationship['relationType'];
                    $displayLabel = $relationship['displayLabel'];

                    if ($relationshipType == 'belongsTo') {
                        $localObject = $localModel::where($findColumn, $record->{$findColumn})->first();

                        $datas = DBM::Object()->setCommonRelation($localObject, $foreignModel, $foreignKey, $localKey)->belongs_to;

                        $record->{$field->name} = $datas;
                        $field->displayLabel = $displayLabel;
                        $field->localKey = $localKey;
                        $field->foreignKey = $foreignKey;
                        $field->relationshipType = $relationshipType;
                    } elseif ($relationshipType == 'hasOne') {
                        $localObject = $localModel::where($findColumn, $record->{$findColumn})->first();
                        $datas = DBM::Object()->setCommonRelation($localObject, $foreignModel, $foreignKey, $localKey)->has_one;

                        $record->{$field->name} = $datas;
                        $field->displayLabel = $displayLabel;
                        $field->localKey = $localKey;
                        $field->foreignKey = $foreignKey;
                        $field->relationshipType = $relationshipType;
                    } elseif ($relationshipType == 'hasMany') {
                        $localObject = $localModel::where($findColumn, $record->{$findColumn})->first();
                        $datas = DBM::Object()->setCommonRelation($localObject, $foreignModel, $foreignKey, $localKey)->has_many;

                        $record->{$field->name} = $datas;
                        $field->displayLabel = $displayLabel;
                        $field->localKey = $localKey;
                        $field->foreignKey = $foreignKey;
                        $field->relationshipType = $relationshipType;
                    } elseif ($relationshipType == 'belongsToMany') {
                        $pivotTable = $relationship['pivotTable'];
                        $parentPivotKey = $relationship['parentPivotKey'];
                        $relatedPivotKey = $relationship['relatedPivotKey'];

                        $localObject = $localModel::where($findColumn, $record->{$findColumn})->first();

                        $datas = DBM::Object()->setManyToManyRelation($localObject, $foreignModel, $pivotTable, $parentPivotKey, $relatedPivotKey)->belongs_to_many;

                        $record->{$field->name} = $datas;
                        $field->displayLabel = $displayLabel;
                        $field->localKey = $localKey;
                        $field->foreignKey = $foreignKey;
                        $field->relationshipType = $relationshipType;
                    }
                }
            }
        }

        return $records;
    }

    /**
     * Create new Relationship.
     *
     * @param \Illuminate\Support\Collection $fields
     * @param object $columns
     * @param \CodexShaper\DBM\Models\DBM_Object|\CodexShaper\DBM\Models\DBM_MongoObject $object
     * @param  object $table
     *
     * @return void
     */
    public function storeRelationshipData($fields, $columns, $object, $table)
    {
        foreach ($fields as $field) {
            if (isset($field->relationship) && $field->relationship->relationType == 'belongsToMany') {
                $relationship = $field->relationship;

                $localModel = $relationship->localModel;
                $localTable = $relationship->localTable;
                $foreignModel = $relationship->foreignModel;
                $pivotTable = $relationship->pivotTable;
                $parentPivotKey = $relationship->parentPivotKey;
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

    /**
     * Update Relationship.
     *
     * @param \Illuminate\Support\Collection $fields
     * @param object $columns
     * @param \CodexShaper\DBM\Models\DBM_Object|\CodexShaper\DBM\Models\DBM_MongoObject $object
     * @param  object $table
     *
     * @return void
     */
    public function updateRelationshipData($fields, $columns, $object, $table)
    {
        foreach ($fields as $field) {
            if (isset($field->relationship)) {
                $relationship = $field->relationship;

                $localModel = $relationship->localModel;
                $localTable = $relationship->localTable;
                $foreignModel = $relationship->foreignModel;

                if ($field->relationship->relationType == 'belongsToMany') {
                    $pivotTable = $relationship->pivotTable;
                    $parentPivotKey = $relationship->parentPivotKey;
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

    /**
     * Remove Relationship.
     *
     * @param object $field
     * @param \CodexShaper\DBM\Models\DBM_Object|\CodexShaper\DBM\Models\DBM_MongoObject $object
     * @param  object $table
     *
     * @return void
     */
    public function removeRelationshipData($field, $object, $table)
    {
        if ($field->type == 'relationship') {
            $relationship = $field->settings;

            $localModel = $relationship->localModel;
            $foreignModel = $relationship->foreignModel;

            $findColumn = $object->details['findColumn'];

            $localObject = $localModel::where($findColumn, $table->{$findColumn})->first();

            if ($relationship->relationType == 'belongsToMany') {
                $pivotTable = $relationship->pivotTable;
                $parentPivotKey = $relationship->parentPivotKey;
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
            } elseif ($relationship->relationType == 'hasMany') {
                $foreignKey = $relationship->foreignKey;
                $localKey = $relationship->localKey;

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
}
