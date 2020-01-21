<?php

namespace CodexShaper\DBM\Database\Types\Mysql;

use CodexShaper\DBM\Database\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class BitType extends Type
{
    const NAME = 'bit';

    /**
     * Register bit type.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $length = empty($fieldDeclaration['length']) ? 1 : $fieldDeclaration['length'];
        $length = $length > 64 ? 64 : $length;

        return "bit({$length})";
    }
}
