<?php

namespace CodexShaper\DBM\Database\Drivers\MongoDB;

use MongoDB\BSON\Binary;
use MongoDB\BSON\Decimal128 as Decimal;
use MongoDB\BSON\Javascript;
use MongoDB\BSON\MaxKey;
use MongoDB\BSON\MinKey;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Regex;
use MongoDB\BSON\Timestamp;
use MongoDB\BSON\UTCDateTime;

class Type
{
    protected static $types = [
        'binary',
        'decimal',
        'javascript',
        'maxKey',
        'minKey',
        'objectId',
        'regex',
        'timestamp',
        'dateTime',
        'string',
        'arrayType',
        'object',
        'boolean',
        'double',
        'null',
        'integer',
        'longInteger',
        'relationship',
    ];

    /**
     * Get binary.
     *
     * @return  \MongoDB\BSON\Binary
     */
    public static function binary(string $value, int $type = Binary::TYPE_GENERIC)
    {
        return new Binary($value, $type);
    }

    /**
     * Get decimal.
     *
     * @return  \MongoDB\BSON\Decimal128
     */
    public static function decimal(string $value)
    {
        return new Decimal($value);
    }

    /**
     * Get javascript.
     *
     * @return  \MongoDB\BSON\Javascript
     */
    public static function javascript(string $code, array $scope = [])
    {
        return new Javascript($code, $scope);
    }

    /**
     * Get max key.
     *
     * @return  \MongoDB\BSON\MaxKey
     */
    public static function maxKey()
    {
        return new MaxKey();
    }

    /**
     * Get min key.
     *
     * @return  \MongoDB\BSON\MinKey
     */
    public static function minKey()
    {
        return new MinKey();
    }

    /**
     * Get ObjectId.
     *
     * @return  \MongoDB\BSON\ObjectId
     */
    public static function objectId(string $value)
    {
        return new ObjectId($value);
    }

    /**
     * Get regular expression.
     *
     * @return  \MongoDB\BSON\Regex
     */
    public static function regex(string $pattern, string $flags = '')
    {
        return new Regex($pattern, $flags);
    }

    /**
     * Get timestamp.
     *
     * @return  \MongoDB\BSON\Timestamp
     */
    public static function timestamp(int $increment, int $timestamp)
    {
        return new Timestamp($increment, $timestamp);
    }

    /**
     * Get datetime.
     *
     * @param int|null $milliseconds
     *
     * @return  \MongoDB\BSON\UTCDateTime
     */
    public static function dateTime($milliseconds = null)
    {
        if (! is_int($milliseconds) || ! is_float($milliseconds) || ! is_string($milliseconds) || ! $milliseconds instanceof \DateTimeInterface) {
            throw new \Exception($milliseconds.' integer or float or string or instance of DateTimeInterface');
        }

        return new UTCDateTime($milliseconds);
    }

    /**
     * Get string.
     *
     * @param string $value
     *
     * @return  string
     */
    public static function string($value)
    {
        return (string) $value;
    }

    /**
     * Get array.
     *
     * @param array $value
     *
     * @return  array
     */
    public static function arrayType($value)
    {
        return (array) $value;
    }

    /**
     * Get object.
     *
     * @param object $value
     *
     * @return  object
     */
    public static function object($value)
    {
        return (object) $value;
    }

    /**
     * Get boolean.
     *
     * @param bool $value
     *
     * @return  bool
     */
    public static function boolean($value)
    {
        return (bool) $value;
    }

    /**
     * Get double.
     *
     * @param float $value
     *
     * @return  float
     */
    public static function double($value)
    {
        return (float) $value;
    }

    /**
     * Get null.
     *
     * @return  null
     */
    public static function null()
    {
    }

    /**
     * Get integer.
     *
     * @param int $value
     *
     * @return  int
     */
    public static function integer($value)
    {
        return (int) $value;
    }

    /**
     * Get long integer.
     *
     * @param int $value
     *
     * @return  int
     */
    public static function longInteger($value)
    {
        return (int) $value;
    }

    /**
     * Get types.
     *
     * @return  array
     */
    public static function getTypes()
    {
        return (array) self::$types;
    }
}
