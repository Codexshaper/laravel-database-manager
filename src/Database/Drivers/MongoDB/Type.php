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
    public static function binary(string $value, int $type = Binary::TYPE_GENERIC)
    {
        return new Binary($value, $type);
    }

    public static function decimal(string $value)
    {
        return new Decimal($value);
    }

    public static function javascript(string $code, $scope = [])
    {
        if (!is_array($scope) || !is_array()) {
            throw new \Exception($scope . " should be array or object");

        }

        return new Javascript($code, $scope);
    }

    public static function maxKey()
    {
        return new MaxKey();
    }

    public static function minKey()
    {
        return new MinKey();
    }

    public static function objectId(string $value)
    {
        return new ObjectId($value);
    }

    public static function regex(string $pattern, string $flags)
    {
        return new Regex($value);
    }

    public static function timestamp(int $increment, int $timestamp)
    {
        return new Timestamp($increment, $timestamp);
    }

    public static function dateTime($milliseconds = null)
    {
        if (!is_int($milliseconds) || !is_float($milliseconds) || !is_string($milliseconds) || !$milliseconds instanceof \DateTimeInterface) {
            throw new \Exception($milliseconds . " integer or float or string or instance of DateTimeInterface");

        }
        return new UTCDateTime($milliseconds);
    }

    /*
     * Custom Types
     */

    public static function string($value)
    {
        return (string) $value;
    }

    public static function arrayType($value)
    {
        return (array) $value;
    }

    public static function object($value)
    {
        return (object) $value;
    }

    public static function boolean($value)
    {
        return (boolean) $value;
    }

    public static function double($value)
    {
        return (double) $value;
    }

    public static function null()
    {
        return null;
    }

    public static function integer($value)
    {
        return (int) $value;
    }

    public static function longInteger($value)
    {
        return (int) $value;
    }

    public static function relationship($value)
    {
        return $value;
    }

    public static function getTypes()
    {
        return (array) self::$types;
    }
}
