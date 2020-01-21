<?php

namespace CodexShaper\DBM\Database\Types\Postgresql;

use CodexShaper\DBM\Database\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class RealType extends Type
{
    const NAME = 'real';
    const DBTYPE = 'float4';

    /**
     * Register real type.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'real';
    }
}
