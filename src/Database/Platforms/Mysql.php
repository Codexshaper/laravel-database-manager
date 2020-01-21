<?php

namespace CodexShaper\DBM\Database\Platforms;

use CodexShaper\DBM\Database\Types\Type;
use Illuminate\Support\Collection;

abstract class Mysql extends Platform
{
    /**
     * Get Types.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getTypes(Collection $typeMapping)
    {
        $typeMapping->forget([
            'real', // same as double
            'int', // same as integer
            'string', // same as varchar
            'numeric', // same as decimal
        ]);

        return $typeMapping;
    }

    /**
     * Register Custom Type Options.
     *
     * @return void
     */
    public static function registerCustomTypeOptions()
    {
        // Not supported
        Type::registerCustomOption(Type::NOT_SUPPORTED, true, [
            'enum',
            'set',
        ]);

        // Not support index
        Type::registerCustomOption(Type::NOT_SUPPORT_INDEX, true, '*text');
        Type::registerCustomOption(Type::NOT_SUPPORT_INDEX, true, '*blob');

        // Disable Default for unsupported types
        Type::registerCustomOption('default', [
            'disabled' => true,
        ], '*text');
        Type::registerCustomOption('default', [
            'disabled' => true,
        ], '*blob');
        Type::registerCustomOption('default', [
            'disabled' => true,
        ], 'json');
    }
}
