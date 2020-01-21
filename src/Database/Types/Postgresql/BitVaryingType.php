<?php

namespace CodexShaper\DBM\Database\Types\Postgresql;

use CodexShaper\DBM\Database\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class BitVaryingType extends Type
{
    const NAME = 'bit varying';
    const DBTYPE = 'varbit';

    /**
     * Register bit varying type.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $length = empty($fieldDeclaration['length']) ? 255 : $fieldDeclaration['length'];

        return "varbit({$length})";
    }
}
