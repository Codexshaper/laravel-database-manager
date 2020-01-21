<?php

namespace CodexShaper\DBM\Database\Types\Mysql;

use CodexShaper\DBM\Database\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class VarBinaryType extends Type
{
    const NAME = 'varbinary';

    /**
     * Register varbinary type.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        $fieldDeclaration['length'] = empty($fieldDeclaration['length']) ? 255 : $fieldDeclaration['length'];

        return "varbinary({$fieldDeclaration['length']})";
    }
}
