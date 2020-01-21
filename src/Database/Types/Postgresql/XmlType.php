<?php

namespace CodexShaper\DBM\Database\Types\Postgresql;

use CodexShaper\DBM\Database\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class XmlType extends Type
{
    const NAME = 'xml';

    /**
     * Register xml type.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'xml';
    }
}
