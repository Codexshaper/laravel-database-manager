<?php

namespace CodexShaper\DBM\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Relationships
{
    /*@var object*/
    protected $parent;
    /*@var string|object*/
    protected $related;
    /*@var string|null*/
    protected $foreignKey;
    /*@var string|null*/
    protected $localKey;
    /*@var string|null*/
    protected $relation;
    /*@var string|null*/
    protected $pivotTable;
    /*@var string|null*/
    protected $parentPivotKey;
    /*@var string|null*/
    protected $relatedPivotKey;
    /*@var string|null*/
    protected $parentKey;
    /*@var string|null*/
    protected $relatedKey;
    /*@var string*/
    protected $through;
    /*@var string|null*/
    protected $firstKey;
    /*@var string|null*/
    protected $secondKey;
    /*@var string|null*/
    protected $secondLocalKey;
    /*@var string*/
    protected $morphName;
    /*@var string|null*/
    protected $morphType;
    /*@var string|null*/
    protected $morphId;
    /*@var string|null*/
    protected $morphLocalKey;
    /*@var string|null*/
    protected $inverse;

    /**
     * Set Common Relation.
     *
     * @param  object  $parent
     * @param  string|object  $related
     * @param  string|null  $foreignKey
     * @param  string|null  $localKey
     * @param  string|null  $relation
     *
     * @return $this
     */
    public function setCommonRelation($parent, $related, $foreignKey = null, $localKey = null, $relation = null)
    {
        $this->parent = $parent;
        $this->related = $related;
        $this->foreignKey = $foreignKey;
        $this->localKey = $localKey;
        $this->relation = $relation;

        return $this;
    }

    /**
     * Set Many to Many Relation.
     *
     * @param  object  $parent
     * @param  string|object  $related
     * @param  string|null  $pivotTable
     * @param  string|null  $parentPivotKey
     * @param  string|null  $relatedPivotKey
     * @param  string|null  $parentKey
     * @param  string|null  $relatedKey
     * @param  string|null  $relation
     *
     * @return $this
     */
    public function setManyToManyRelation(
        $parent,
        $related,
        $pivotTable = null,
        $parentPivotKey = null,
        $relatedPivotKey = null,
        $parentKey = null,
        $relatedKey = null,
        $relation = null)
    {
        $this->parent = $parent;
        $this->related = $related;
        $this->pivotTable = $pivotTable;
        $this->parentPivotKey = $parentPivotKey;
        $this->relatedPivotKey = $relatedPivotKey;
        $this->parentKey = $parentKey;
        $this->relatedKey = $relatedKey;
        $this->relation = $relation;

        return $this;
    }

    /**
     * Set Has Through Relation.
     *
     * @param  object  $parent
     * @param  string|object  $related
     * @param  string  $through
     * @param  string|null  $firstKey
     * @param  string|null  $secondKey
     * @param  string|null  $localKey
     * @param  string|null  $secondLocalKey
     *
     * @return $this
     */
    public function setHasThroughRelation(
        $parent,
        $related,
        $through,
        $firstKey = null,
        $secondKey = null,
        $localKey = null,
        $secondLocalKey = null)
    {
        $this->parent = $parent;
        $this->related = $related;
        $this->through = $through;
        $this->firstKey = $firstKey;
        $this->secondKey = $secondKey;
        $this->localKey = $localKey;
        $this->secondLocalKey = $secondLocalKey;

        return $this;
    }

    /**
     * Set Morph Relation.
     *
     * @param  object  $parent
     * @param  string|object  $related
     * @param  string  $morphName
     * @param  string|null  $morphType
     * @param  string|null  $morphId
     * @param  string|null  $morphLocalKey
     *
     * @return $this
     */
    public function setMorphRelation(
        $parent,
        $related,
        $morphName,
        $morphType = null,
        $morphId = null,
        $morphLocalKey = null)
    {
        $this->parent = $parent;
        $this->related = $related;
        $this->morphName = $morphName;
        $this->morphType = $morphType;
        $this->morphId = $morphId;
        $this->morphLocalKey = $morphLocalKey;

        return $this;
    }

    /**
     * Set MorphTo Relation.
     *
     * @param  object  $parent
     * @param  string|null  $morphName
     * @param  string|null  $morphType
     * @param  string|null  $morphId
     * @param  string|null  $morphLocalKey
     *
     * @return $this
     */
    public function setMorphToRelation(
        $parent,
        $morphName = null,
        $morphType = null,
        $morphId = null,
        $morphLocalKey = null)
    {
        $this->parent = $parent;
        $this->morphName = $morphName;
        $this->morphType = $morphType;
        $this->morphId = $morphId;
        $this->morphLocalKey = $morphLocalKey;

        return $this;
    }

    /**
     * Set MorphToMany Relation.
     *
     * @param  object  $parent
     * @param  string|object  $related
     * @param  string  $morphName
     * @param  string|null  $pivotTable
     * @param  string|null  $parentPivotKey
     * @param  string|null  $relatedPivotKey
     * @param  string|null  $parentKey
     * @param  string|null  $relatedKey
     * @param  string|null  $inverse
     *
     * @return $this
     */
    public function setMorphToManyRelation(
        $parent,
        $related,
        $morphName,
        $pivotTable = null,
        $parentPivotKey = null,
        $relatedPivotKey = null,
        $parentKey = null,
        $relatedKey = null,
        $inverse = false)
    {
        $this->parent = $parent;
        $this->related = $related;
        $this->morphName = $morphName;
        $this->pivotTable = $pivotTable;
        $this->parentPivotKey = $parentPivotKey;
        $this->relatedPivotKey = $relatedPivotKey;
        $this->parentKey = $parentKey;
        $this->relatedKey = $relatedKey;
        $this->inverse = $inverse;

        return $this;
    }

    /*
     * Relationship.
     */
    public function has_one(): HasOne
    {
        return $this->parent->hasOne($this->related, $this->foreignKey, $this->localKey);
    }

    public function has_many(): HasMany
    {
        return $this->parent->hasMany($this->related, $this->foreignKey, $this->localKey);
    }

    public function belongs_to_many(): BelongsToMany
    {
        return $this->parent->belongsToMany(
            $this->related,
            $this->pivotTable,
            $this->parentPivotKey,
            $this->relatedPivotKey,
            $this->parentKey,
            $this->relatedKey,
            $this->relation
        );
    }

    public function belongs_to(): BelongsTo
    {
        return $this->parent->belongsTo($this->related, $this->foreignKey, $this->localKey, $this->relation);
    }

    public function has_one_through(): HasOneThrough
    {
        return $this->parent->hasOneThrough(
            $this->related,
            $this->through,
            $this->firstKey,
            $this->secondKey,
            $this->localKey,
            $this->secondLocalKey
        );
    }

    public function has_many_through(): HasManyThrough
    {
        return $this->parent->hasManyThrough(
            $this->related,
            $this->through,
            $this->firstKey,
            $this->secondKey,
            $this->localKey,
            $this->secondLocalKey
        );
    }

    public function morph_one(): MorphOne
    {
        return $this->parent->morphOne(
            $this->related,
            $this->morphName,
            $this->morphType,
            $this->morphId,
            $this->morphLocalKey
        );
    }

    public function morph_to(): MorphTo
    {
        return $this->parent->morphTo(
            $this->morphName,
            $this->morphType,
            $this->morphId,
            $this->morphLocalKey
        );
    }

    public function morph_many(): MorphMany
    {
        return $this->parent->morphMany(
            $this->related,
            $this->morphName,
            $this->morphType,
            $this->morphId,
            $this->morphLocalKey
        );
    }

    public function morph_to_many(): MorphToMany
    {
        return $this->parent->morphToMany(
            $this->related,
            $this->morphName,
            $this->pivotTable,
            $this->parentPivotKey,
            $this->relatedPivotKey,
            $this->parentKey,
            $this->relatedKey,
            $this->inverse
        );
    }

    public function morphed_by_many(): MorphToMany
    {
        return $this->parent->morphedByMany(
            $this->related,
            $this->morphName,
            $this->pivotTable,
            $this->parentPivotKey,
            $this->relatedPivotKey,
            $this->parentKey,
            $this->relatedKey
        );
    }
}
