<?php

namespace CodexShaper\DBM\Database\Types\Postgresql;

use CodexShaper\DBM\Database\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TsQueryType extends Type
{
    const NAME = 'tsquery';

    /**
     * Register tsquery type.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'tsquery';
    }
}
