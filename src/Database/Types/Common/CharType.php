<?php

namespace CodexShaper\DBM\Database\Types\Common;

use CodexShaper\DBM\Database\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class CharType extends Type
{
    const NAME = 'char';

    /**
     * Register char type.
     *
     * @param array $field
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $fieldDeclaration['length'] = empty($fieldDeclaration['length']) ? 1 : $fieldDeclaration['length'];

        return "char({$fieldDeclaration['length']})";
    }
}
