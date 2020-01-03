<?php

namespace CodexShaper\DBM\Traits;

use CodexShaper\DBM\Database\Drivers\MongoDB\Type;
use CodexShaper\DBM\Facades\Manager as DBM;

trait RecordRelationship
{
    public function prepareRelationshipData($records, $browseFields, $object)
    {
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

        return $records;
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
}
