<?php

namespace CodexShaper\DBM\Database\Drivers\MongoDB\Traits;

use MongoDB\Model\IndexInfo;

trait IndexTrait
{
    /**
     * get common types.
     *
     * @return  string
     */
    protected static function getCommonType(IndexInfo $index)
    {
        $type = '';

        if ($index->isText()) {
            $type = 'TEXT';
        } elseif ($index->is2dSphere()) {
            $type = '2DSPARSE';
        } elseif ($index->isTtl()) {
            $type = 'TTL';
        } elseif ($index->isGeoHaystack()) {
            $type = 'GEOHAYSTACK';
        }

        return $type;
    }

    /**
     * get special types.
     *
     * @return  string
     */
    protected static function getSpecialType(IndexInfo $index)
    {
        if (static::checkUnique($index)) {
            return 'UNIQUE';
        }

        if (static::checkUniqueDesc($index)) {
            return 'UNIQUE_DESC';
        }

        if (static::checkSparse($index)) {
            return 'SPARSE';
        }
        if (static::checkSparseUnique($index)) {
            return 'SPARSE_UNIQUE';
        }
        if (static::checkSparseUniqueDesc($index)) {
            return 'SPARSE_UNIQUE_DESC';
        }

        if (static::checkSparseDesc($index)) {
            return 'SPARSE_DESC';
        }

        return '';
    }

    /**
     * get defaults types.
     *
     * @return  string
     */
    protected static function getDefaultType(IndexInfo $index)
    {
        $name = $index->getName();
        $partials = explode('_', $name);
        $type = end($partials);

        if ($type == 'asc') {
            return 'ASC';
        } elseif ($type == 'index') {
            return 'INDEX';
        } elseif ($type == 'desc') {
            return 'DESC';
        }

        return '';
    }

    /**
     * check Unique.
     *
     * @return  bool
     */
    protected static function checkUnique(IndexInfo $index)
    {
        return $index->isUnique() && ! $index->isSparse() && ! static::checkDescending($index) ? true : false;
    }

    /**
     * check Unique Descending.
     *
     * @return  bool
     */
    protected static function checkUniqueDesc(IndexInfo $index)
    {
        return $index->isUnique() && ! $index->isSparse() && static::checkDescending($index) ? true : false;
    }

    /**
     * check Sparse.
     *
     * @return  bool
     */
    protected static function checkSparse(IndexInfo $index)
    {
        return $index->isSparse() && ! static::checkDescending($index) ? true : false;
    }

    /**
     * check Sparse Unique.
     *
     * @return  bool
     */
    protected static function checkSparseUnique(IndexInfo $index)
    {
        return $index->isSparse() && $index->isUnique() && ! static::checkDescending($index) ? true : false;
    }

    /**
     * check Sparse Unique Descending.
     *
     * @return  bool
     */
    protected static function checkSparseUniqueDesc(IndexInfo $index)
    {
        return $index->isSparse() && $index->isUnique() && static::checkDescending($index) ? true : false;
    }

    /**
     * check Sparse Descending.
     *
     * @return  bool
     */
    protected static function checkSparseDesc(IndexInfo $index)
    {
        return $index->isSparse() && static::checkDescending($index) ? true : false;
    }

    /**
     * check Descending.
     *
     * @return  bool
     */
    protected static function checkDescending(IndexInfo $index)
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
