<?php

namespace CodexShaper\DBM\Database\Types\Mysql;

use CodexShaper\DBM\Database\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class TinyBlobType extends Type
{
    const NAME = 'tinyblob';

    /**
     * Register tinyblob type.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'tinyblob';
    }
}
