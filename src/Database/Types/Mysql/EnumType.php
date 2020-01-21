<?php

namespace CodexShaper\DBM\Database\Types\Mysql;

use CodexShaper\DBM\Database\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Illuminate\Support\Facades\DB;

class EnumType extends Type
{
    const NAME = 'enum';

    /**
     * Register enum type.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        throw new \Exception('Enum type is not supported');
        $pdo = DB::connection()->getPdo();

        // trim the values
        $allowed = array_map(function ($value) use ($pdo) {
            return $pdo->quote(trim($value));
        }, $allowed);

        return 'enum('.implode(', ', $allowed).')';
    }
}
