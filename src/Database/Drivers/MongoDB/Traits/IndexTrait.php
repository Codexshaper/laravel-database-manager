<?php

namespace CodexShaper\DBM\Database\Drivers\MongoDB\Traits;

use MongoDB\Model\IndexInfo;

trait IndexTrait
{

    protected static function getCommonType(IndexInfo $index)
    {
        $type = "";

        if ($index->isText()) {
            $type = "TEXT";
        } else if ($index->is2dSphere()) {
            $type = "2DSPARSE";
        } else if ($index->isTtl()) {
            $type = "TTL";
        } else if ($index->isGeoHaystack()) {
            $type = "GEOHAYSTACK";
        }

        return $type;
    }

    protected static function getSpecialType(IndexInfo $index)
    {
        if (static::checkUnique($index)) {
            return "UNIQUE";
        }

        if (static::checkUniqueDesc($index)) {
            return "UNIQUE_DESC";
        }

        if (static::checkSparse($index)) {
            return "SPARSE";
        }
        if (static::checkSparseUnique($index)) {
            return "SPARSE_UNIQUE";
        }
        if (static::checkSparseUniqueDesc($index)) {
            return "SPARSE_UNIQUE_DESC";
        }

        if (static::checkSparseDesc($index)) {
            return "SPARSE_DESC";
        }

        return "";
    }

    protected static function getDefaultType(IndexInfo $index)
    {
        $name     = $index->getName();
        $partials = explode("_", $name);
        $type     = end($partials);

        if ($type == 'asc') {
            return "ASC";
        } else if ($type == 'index') {
            return "INDEX";
        } else if ($type == 'desc') {
            return "DESC";
        }

        return "";
    }

    protected static function checkUnique($index)
    {
        return $index->isUnique() && !$index->isSparse() && !static::checkDescending($index) ? true : false;
    }

    protected static function checkUniqueDesc($index)
    {
        return $index->isUnique() && !$index->isSparse() && static::checkDescending($index) ? true : false;
    }

    protected static function checkSparse($index)
    {
        return $index->isSparse() && !static::checkDescending($index) ? true : false;
    }

    protected static function checkSparseUnique($index)
    {
        return $index->isSparse() && $index->isUnique() && !static::checkDescending($index) ? true : false;
    }

    protected static function checkSparseUniqueDesc($index)
    {
        return $index->isSparse() && $index->isUnique() && static::checkDescending($index) ? true : false;
    }

    protected static function checkSparseDesc($index)
    {
        return $index->isSparse() && static::checkDescending($index) ? true : false;
    }

    protected static function checkDescending($index)
    {
        $keys = $index->getKey();

        foreach ($keys as $key => $value) {
            if ($value == -1) {
                return true;
            }
        }

        return false;
    }
}
